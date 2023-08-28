<?php

namespace App\Query;

use PDO;
use PDOStatement;

class Query
{
    private static ? PDO $pdo = null;

    /**
     * @param string $sql
     * @return PDOStatement
     */
    public static function prepare( string $sql ) : PDOStatement {
        $pdo = Query::getPdo();
        return $pdo->prepare( $sql );
    }

    /**
     * Begin transaction, should be followed by either commit on ok or rollback on failure !
     * @return bool
     */
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
     * @return 1st row or |null on empty
     */
    protected static function select( PDOStatement  & $stmt, array & $args = [] ): ? array {
        if( $stmt->execute( $args ) ) {
            $data = $stmt->fetch(); // get first row
            if( $data ) { // could be false
                return $data;
            }
        }
        return null;
    }

    /**
     * @param PDOStatement $stmt
     * @param array $args
     * @return array of all rows found or empty array
     */
    protected static function selectArray(PDOStatement & $stmt, array & $args = [] ) : array { // array of objects, could be empty
        if ($stmt->execute($args)) {
            return $stmt->fetchAll();
        }
        return [];
    }

    /**
     * @param PDOStatement $stmt
     * @param array $args
     * @return id of newly inserted row or null on failure
     */
    protected static function insert( PDOStatement & $stmt, array & $args ) : ? int { // returns new id
        return $stmt->execute( $args ) ? Query::lastInsertId() : null;
    }

    /**
     * @param PDOStatement $stmt
     * @param array $args
     * @return bool true on success
     */
    protected static function update( PDOStatement & $stmt, array & $args ) : bool {
        return $stmt->execute( $args );
    }

    /**
     * @param PDOStatement $stmt
     * @param array $args
     * @return bool, true on success
     */
    protected static function delete(PDOStatement & $stmt, array & $args ) : bool {
        return $stmt->execute( $args );
    }

    /**
     * @param string|null $name
     * needs to called right after an insert statement, Query::insert already does so
     * @return last inserted id,
     */
    protected static function lastInsertId( ? string $name = null ) : int {
        return Query::getPdo()->lastInsertId( $name );
    }

//*** private ****
    private static function getPdo() {
        if( ! Query::$pdo ) { // create pdo if not exists
            Query::$pdo = new PDO(
                'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8', DB_USER, DB_PASSWORD
            );
            Query::$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            Query::$pdo->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC ); // return associated array only
//            Query::$pdo->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // should be the default true for allowing multiple :year in prepare
            Query::$pdo->setAttribute( PDO::ATTR_STRINGIFY_FETCHES, false ); // also to get text and numbers instead of always text as template
        }
        return Query::$pdo;
    }
}