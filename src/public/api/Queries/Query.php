<?php

namespace App\Queries;

use PDO;

class Query
{
    private static PDO $pdo;

    private string $host = 'localhost'; // TODO move to config file, ignored
    private string $database = 'zbo';

    private string $user = 'zbo';
    private string $password = 'zbo';

    private function getPdo() {
        if( ! isset( Query::$pdo ) ) {
            $driver = 'mysql:host='.$this->host.';dbname='.$this->database.';charset=utf8';
            Query::$pdo = new PDO( $driver, $this->user, $this->password);
            Query::$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            Query::$pdo->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC );
            Query::$pdo->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // get text and numbers instead of always text as result
            Query::$pdo->setAttribute( PDO::ATTR_STRINGIFY_FETCHES, false ); // also to get text and numbers instead of always text as result
        }
        return Query::$pdo;
    }

    public function get( string & $sql, array & $args ) : ? array { // object or null
        $pdo = Query::getPdo();
        $stmt = $pdo->prepare( $sql );
        if( $stmt->execute( $args ) ) {
            $data = $stmt->fetch();
            if( $data ) {
                return $data;
            }
        }
        return null;
    }

    public function getArray( string & $sql, array & $args ) : array { // array of objects, could be empty
        $pdo = Query::getPdo();
        $stmt = $pdo->prepare( $sql );
        if( $stmt->execute( $args ) ) {
            return $stmt->fetchAll();
        }
        return [];
    }

    public function insert( string & $sql, array & $args ) : ? int { // returns new id
        $pdo = Query::getPdo();
        $stmt = $pdo->prepare( $sql );
        return $stmt->execute( $args ) ? $pdo->lastInsertId() : null;
    }

    public function update( string & $sql, array & $args ) : bool {
        $pdo = Query::getPdo();
        $stmt = $pdo->prepare( $sql );
        return $stmt->execute( $args );
    }

    public function delete( string & $sql, array & $args ) : bool {
        $pdo = Query::getPdo();
        $stmt = $pdo->prepare( $sql );
        return $stmt->execute( $args );
    }


}