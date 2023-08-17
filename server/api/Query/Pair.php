<?php

namespace App\Query;

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


    public static function del( int $id ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            DELETE 
            FROM pair
            WHERE id=:id
        ' );
        return Query::delete( $stmt, $args );
    }





    // for results page
    public static function options( int $breederId, int $year ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( " 
            SELECT id, breederId, `year`, name 
            FROM pair
            WHERE breederId=:breederId AND `year`=:year
            ORDER BY breederId, name
        " );
        return Query::selectArray( $stmt, $args );
    }

}