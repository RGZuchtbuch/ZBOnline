<?php

namespace App\model;

class Cache extends Query
{

    public static function get( string $controller, string $url, string $query ) {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            SELECT json
            FROM _cache
            WHERE controller=:controller AND url=:url AND query=:query
        ');
        $cache = Query::select($stmt, $args);
        if( $cache != NULL ) {
            return $cache['json'];
        }
        return null;
    }

    public static function set( string $controller, string $url, string $query, string $json ) {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            REPLACE INTO _cache ( controller, url, query, json )
            VALUES ( :controller, :url, :query, :json )
        ');

        return Query::insert($stmt, $args ); // returns success
    }

    public static function del( string $controller ): bool
    {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            DELETE FROM _cache
            WHERE controller=:controller
        ');
        return Query::delete($stmt, $args ); // returns success
    }
}

