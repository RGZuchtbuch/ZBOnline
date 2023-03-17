<?php

namespace App\Query;



use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpNotImplementedException;

class Breed extends Query
{
    public static function new( string $name, int $sectionId, int $broodGroup, int $lay, int $eggWeight, int $sireRing, int $dameRing, int $sireWeight, int $dameWeight, string $info ) : ? int {
        throw new HttpNotImplementedException( null, "oops" );
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


    public static function set( int $id, string $name, int $sectionId, int $broodGroup, int $lay, int $eggWeight, int $sireRing, int $dameRing, int $sireWeight, int $dameWeight, string $info ) : bool {
        throw new HttpNotImplementedException( null, "oops" );
    }

    public static function del( int $id ) : bool {
        throw new HttpNotImplementedException( null, "oops" );
    }


    public static function colors( int $id ) : array {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            SELECT id, name, breedId, info
            FROM color
            WHERE breedId=:id
            ORDER BY name
        ');
        return Query::selectArray($stmt, $args);
    }

}
