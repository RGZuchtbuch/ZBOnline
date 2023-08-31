<?php

namespace App\query;

use Slim\Exception\HttpNotImplementedException;

class Breed extends Query
{
    public static function new( string $name, int $sectionId, ? int $broodGroup, ? int $lay, ? int $layWeight, ? int $sireRing, ? int $dameRing, ? int $sireWeight, ? int $dameWeight, ? string $info, int $modifierId ) : ? int
    {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
        INSERT INTO breed ( name, sectionId, broodGroup, lay, layWeight, sireRing, dameRing, sireWeight, dameWeight, info, modifierId ) 
            VALUES ( :name, :sectionId, :broodGroup, :lay, :layWeight, :sireRing, :dameRing, :sireWeight, :dameWeight, :info, :modifierId )
        ' );
        return Query::insert( $stmt, $args ); // returns id
    }

    public static function get( int $id ) : ? array
    {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            SELECT id, name, sectionId, broodGroup, lay, layWeight, sireRing, dameRing, sireWeight, dameWeight, info, section.layers AS layer
            FROM breed
            LEFT JOIN section ON section.id = breed.sectionId
            WHERE id=:id
        ');
        return Query::select($stmt, $args);
    }

    public static function set( int $id, string $name, int $sectionId, ? int $broodGroup, ? int $lay, ? int $layWeight, ? int $sireRing, ? int $dameRing, ? int $sireWeight, ? int $dameWeight, ? string $info, int $modifierId ) : bool
    {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            UPDATE breed
            SET name=:name, sectionId=:sectionId, broodGroup=:broodGroup, lay=:lay, layWeight=:layWeight, sireRing=:sireRing, dameRing=:dameRing, sireWeight=:sireWeight, dameWeight=:dameWeight, info=:info, modifierId=:modifierId
            WHERE id=:id
        ' );
        return Query::update( $stmt, $args );
    }

    public static function del( int $id ) : bool {
        throw new HttpNotImplementedException( null, "oops" );
    }

    public static function all() : array {
        $stmt = Query::prepare('
            SELECT id, name, sectionId, broodGroup, lay, layWeight, sireRing, dameRing, sireWeight, dameWeight
            FROM breed
            ORDER BY name
        ');
        return Query::selectArray($stmt );
    }


    public static function colors( int $id ) : array {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            SELECT id, name, breedId
            FROM color
            WHERE breedId=:id
            ORDER BY name
        ');
        return Query::selectArray($stmt, $args);
    }

}
