<?php

namespace App\model;

class Pair extends Query
{
    public static function get( $id ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            SELECT id, breederId, districtId, year, `group`, sectionId, breedId, colorId, name, paired, notes
            FROM pair
            WHERE id=:id
        ' );
        return Query::select( $stmt, $args );
    }

    public static function new( int $breederId, int $districtId, int $year, string $group, int $sectionId, int $breedId, ? int $colorId, string $name, ? string $paired, ? string $notes, int $modifierId ) : ? int {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            INSERT INTO pair ( breederId, districtId, year, `group`, sectionId, breedId, colorId, name, paired, notes, modifierId ) 
            VALUES ( :breederId, :districtId, :year, :group, :sectionId, :breedId, :colorId, :name, :paired, :notes, :modifierId )
        ' );
        return Query::insert( $stmt, $args );
    }


    public static function set( int $id, int $breederId, int $districtId, int $year, string $group, int $sectionId, int $breedId, ? int $colorId, string $name, ? string $paired, ? string $notes, int $modifierId ) : bool {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            UPDATE  pair
            SET breederId=:breederId, districtId=:districtId, year=:year, `group`=:group, sectionId=:sectionId, breedId=:breedId, colorId=:colorId, name=:name, paired=:paired, notes=:notes, modifierId=:modifierId
            WHERE id=:id
        ' );
        return Query::update( $stmt, $args );
    }


    public static function del( int $id ) : bool {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            DELETE 
            FROM pair
            WHERE id=:id
        ' );
        return Query::delete( $stmt, $args );
    }





    // for results page
    public static function allWithBreed( int $id ) : array {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            SELECT id, breedId
            FROM pair
            WHERE breedId=:id
        ' );
        return Query::selectArray( $stmt, $args );
    }

    public static function allWithColor( int $id ) : array {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
                SELECT id, colorId
                FROM pair
                WHERE colorId=:id
            ' );
        return Query::selectArray( $stmt, $args );
    }

}