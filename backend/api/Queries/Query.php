<?php

namespace App\Queries;

use PDO;
use PDOStatement;

class Query
{
    private static PDO $pdo;

    private static string $host = 'localhost'; // TODO move to config file, ignored
    private static string $database = 'zbo';

    private static string $user = 'zbo'; // should be in config file with token secret
    private static string $password = 'zbo';



    public static function prepare( string $sql ) : PDOStatement {
        $pdo = Query::getPdo();
        return $pdo->prepare( $sql );
    }

    public static function begin(): bool {
        $pdo = Query::getPdo();
        return $pdo->beginTransaction();
    }

    public static function commit() : bool {
        $pdo = Query::getPdo();
        return $pdo->commit();
    }

    public static function rollback() : bool {
        $pdo = Query::getPdo();
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
        if( $stmt->execute( $args ) ) {
            return $stmt->fetchAll();
        }
        return [];
    }

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
                        $parent['children'][] = & $value;
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
        return $stmt->execute( $args ) ? Query::getPdo()->lastInsertId() : null;
    }

    public static function update( PDOStatement & $stmt, array & $args ) : bool {
        return $stmt->execute( $args );
    }

    public static function delete( PDOStatement & $stmt, array & $args ) : bool {
        return $stmt->execute( $args );
    }

//*** protected ****


//*** private ****
    private static function getPdo() {
        if( ! isset( Query::$pdo ) ) {
            $config = require( './config.php' ); // get credentials
            Query::$pdo = new PDO(
                'mysql:host='.$config['db' ]['host'].';dbname='.$config['db' ]['name'].';charset=utf8',
                $config['db' ]['user'],
                $config['db' ]['password']
            );
            Query::$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            Query::$pdo->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC );
            Query::$pdo->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // get text and numbers instead of always text as result
            Query::$pdo->setAttribute( PDO::ATTR_STRINGIFY_FETCHES, false ); // also to get text and numbers instead of always text as result
        }
        return Query::$pdo;
    }
}