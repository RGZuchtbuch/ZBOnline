<?php

namespace App\Queries;

use App\Config;
use App\controllers\Controller;
use http\Exception\BadMethodCallException;
use PDO;
use PDOStatement;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class Query
{
    private static PDO $pdo;

    public static function prepare( string $sql ) : PDOStatement {
        $pdo = BaseModel::getPdo();
        return $pdo->prepare( $sql );
    }

    public static function begin(): bool {
        $pdo = BaseModel::getPdo();
        return $pdo->beginTransaction();
    }

    public static function commit() : bool {
        $pdo = BaseModel::getPdo();
        return $pdo->commit();
    }

    public static function rollback() : bool {
        $pdo = BaseModel::getPdo();
        return $pdo->rollBack();
    }

    /**
     * @param PDOStatement $stmt
     * @param array $args
     * @return array|null
     */
    public static function select( PDOStatement  & $stmt, array & $args = [] ): ? array {
        if( $stmt->execute( $args ) ) {
            $data = $stmt->fetch();
            if( $data ) { // could be false
                return $data;
            }
        }
        return null;
    }

    public static function selectArray(PDOStatement & $stmt, array & $args = [] ) : array { // array of objects, could be empty
        if ($stmt->execute($args)) {
            return $stmt->fetchAll();
        }
        return [];
    }

    public static function selectRoot( PDOStatement & $stmt, array & $args = [] ) : ? array
    {
        $root = null;
        if ($stmt->execute($args)) {
            $rows = $stmt->fetchAll();
            $nodes = [];
            foreach( $rows as & $row ) {
                $row[ 'children' ] = []; // all nodes can have children
                $nodes[ $row['id'] ] = & $row;
            }
            foreach( $rows as & $child ) {
                //print( $child['id'].' \n' );
                $parentId = & $child[ 'parentId' ];
                if( $parentId && isset( $nodes[ $parentId ] ) ) { //has and exists in array
                    //print( ' add '.$child['id'].' to '.$parentId.'\n' );
                    $parent = & $nodes[ $parentId ];
//                    if( ! isset( $parent[ 'children' ] ) ) $parent[ 'children' ] = [];
                    $parent[ 'children' ][] = & $child;
                } else {
                    $root = & $child;
                }
            }
        }
        return $root;
    }

    // TODO is needed or should be replaced by selectRoot
    // works on parentId and results parent having children based on parentId
    public static function selectTree(PDOStatement & $stmt, array & $args = [] ) : array { // array of objects, could be empty
        if( $stmt->execute( $args ) ) {
            $values = $stmt->fetchAll();
            $results = [];
            foreach( $values as & $value ) {
                $parentId = $value['parentId'];
                $match = false;
                foreach ($values as & $parent) {
                    if ( $parent['id'] === $parentId ) {
                        $match = true;
                        if( ! isset( $parent['children'] ) ) $parent['children'] = [];
                        $parent['children'][] = & $value; // TODO ref check, does this work ok ? ( could be as passed by ref )
                        break; // done looking
                    }
                }
                if( ! $match ) $results[] = & $value;
            }
            return $results;
        }
        return [];
    }

    public static function insert( PDOStatement & $stmt, array & $args ) : ? int { // returns new id
        return $stmt->execute( $args );
    }

    public static function lastInsertId( ? string $name ) : int {
        return BaseModel::getPdo()->lastInsertId( $name );
    }

    public static function update( PDOStatement & $stmt, array & $args ) : bool {
        return $stmt->execute( $args );
    }

    public static function replace( PDOStatement & $stmt, array & $args ) : bool {
        return $stmt->execute( $args );
    }

    public static function delete( PDOStatement & $stmt, array & $args ) : bool {
        return $stmt->execute( $args );
    }

//*** protected ****

    private static string $host = Config::DB_HOST; //'localhost';
    private static string $database = Config::DB_NAME; // 'zbo';

    private static string $user = Config::DB_USER; //'zbo'; // should be in config file with credentials secret
    private static string $password = Config::DB_PASSWORD; //'zbo';
//*** private ****
    private static function getPdo() {
        if( ! isset( BaseModel::$pdo ) ) {
            BaseModel::$pdo = new PDO(
                'mysql:host='.Config::DB_HOST.';dbname='.Config::DB_NAME.';charset=utf8', Config::DB_USER, Config::DB_PASSWORD
            );
            BaseModel::$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            BaseModel::$pdo->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC );
//            Query::$pdo->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // should be the default true for allowing multiple :year in prepare
            BaseModel::$pdo->setAttribute( PDO::ATTR_STRINGIFY_FETCHES, false ); // also to get text and numbers instead of always text as template
        }
        return BaseModel::$pdo;
    }
}