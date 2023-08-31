<?php

namespace App\query;

use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;

class Log extends Query
{
    public static function new( string $method, string $uri, ? string $query, ? string $body, ? int $requesterId ) : ? int {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            INSERT INTO _log ( method, uri, query, body, modifierId )
            VALUES ( :method, :uri, :query, :body, :requesterId )
        ' );
        return Query::insert( $stmt, $args ); // returns id
    }


    public static function get( int $id ) : ? array {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            SELECT id, title, html
            FROM article
            WHERE id=:id
        ');
        return null; //Query::select($stmt, $args);
    }


    public static function set( int $id,  string $title, string $html, $modifierId ) : bool {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            UPDATE article
            SET title=:title, html=:html, modifierId=:modifierId
            WHERE id=:id
        ' );
        return false; // Query::update( $stmt, $args );
    }


    public static function del( int $id ) : bool {
        return false;
    }

    public static function next( int $from, int $count ) : array {
        // need to concatenate args as it requires constant ints
        $stmt = Query::prepare('
            SELECT *
            FROM _log
            ORDER BY modified DESC
            LIMIT '.$from.','.$count.'
        ');
        return Query::selectArray($stmt );
    }

}
