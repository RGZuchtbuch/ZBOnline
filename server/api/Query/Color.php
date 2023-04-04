<?php

namespace App\Query;

class Color extends Query
{

    public static function new( string $name, int $sectionId, int $broodGroup, int $lay, int $eggWeight, int $sireRing, int $dameRing, int $sireWeight, int $dameWeight, string $info ) : ? int {
        return null;
    }

    public static function get( int $id ) : ? array {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            SELECT *
            FROM breed
            WHERE id=:id
        ');
        return Query::select($stmt, $args);
    }


    public static function set( int $id, string $name, int $sectionId, int $broodGroup, int $lay, int $eggWeight, int $sireRing, int $dameRing, int $sireWeight, int $dameWeight, string $info ) : ? int {
        return false;
    }

    public static function del( int $id ) : bool {
        return false;
    }

    public static function all() : array {
        $stmt = Query::prepare('
            SELECT *
            FROM color
            ORDER BY name;
        ');
        return Query::selectArray($stmt );
    }

}