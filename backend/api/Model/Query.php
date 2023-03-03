<?php

namespace App\Model;

use App\Config;
use PDO;
use PDOStatement;

class Query
{
    private static ? PDO $pdo = null;

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
        if ($stmt->execute($args)) {
            return $stmt->fetchAll();
        }
        return [];
    }

    public static function insert( PDOStatement & $stmt, array & $args ) : ? int { // returns new id
        return $stmt->execute( $args ) ? Query::lastInsertId() : null;
    }

    public static function lastInsertId( ? string $name = null ) : int {
        return Query::getPdo()->lastInsertId( $name );
    }

    public static function update( PDOStatement & $stmt, array & $args ) : bool {
        return $stmt->execute( $args );
    }

    public static function delete( PDOStatement & $stmt, array & $args ) : bool {
        return $stmt->execute( $args );
    }

//*** private ****
    private static function getPdo() {
        if( ! Query::$pdo ) { // create pdo if not exists
            Query::$pdo = new PDO(
                'mysql:host='.Config::DB_HOST.';dbname='.Config::DB_NAME.';charset=utf8', Config::DB_USER, Config::DB_PASSWORD
            );
            Query::$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            Query::$pdo->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC ); // return associated array only
//            Query::$pdo->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // should be the default true for allowing multiple :year in prepare
            Query::$pdo->setAttribute( PDO::ATTR_STRINGIFY_FETCHES, false ); // also to get text and numbers instead of always text as template
        }
        return Query::$pdo;
    }
}