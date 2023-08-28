<?php

namespace App\Query;

class Cache extends Query
{
    public static function replace( string $key, string $params, ? string $json ): ? int
    {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            REPLACE INTO _cache ( `key`, `params`, `json` )
            VALUES ( :key, :params, :json )
        ');

        return Query::insert($stmt, $args ); // returns success
    }

    public static function getJson( string $key, string $params  ): ? string
    {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            SELECT `key`, `params`, `json`
            FROM _cache
            WHERE `key`=:key AND `params`=:params
        ');
        $cache = Query::select($stmt, $args);
        if( $cache != NULL ) {
            return $cache[ 'json' ];
        }
        return null;
    }

    public static function set(int $id, string $title, string $text): bool
    {
        return false;
    }

    public static function del( string $key ): bool
    {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            DELETE FROM _cache
            WHERE `key`=:key
        ');
        return Query::delete($stmt, $args ); // returns success
    }
}

