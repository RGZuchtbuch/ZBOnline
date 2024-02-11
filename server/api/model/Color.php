<?php

namespace App\model;

class Color extends Query
{
	public static function get( int $id ) : ? array {
		$args = get_defined_vars();
		$stmt = Query::prepare('
            SELECT id, name, breedId, aoc, info
            FROM color
            WHERE id=:id
        ');
		return Query::select($stmt, $args);
	}

    public static function new( string $name, int $breedId, ? int $aoc, ? string $info, int $modifierId ) : ? int {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
        INSERT INTO color ( name, breedId, aoc, info, modifierId ) 
            VALUES ( :name, :breedId, :aoc, :info, :modifierId )
        ' );
        return Query::insert( $stmt, $args );
    }
    public static function set( int $id, string $name, ? int $aoc, ? string $info, int $modifierId ) : ? int { // cannot change breed
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            UPDATE color
            SET name=:name, aoc=:aoc, info=:info, modifierId=:modifierId
            WHERE id=:id
        ' );
        return Query::update( $stmt, $args );
    }

    public static function del( int $id ) : bool {
		$args = get_defined_vars();
		$stmt = Query::prepare('
            DELETE 
            FROM color
            WHERE id=:id
        ');
		return Query::delete($stmt, $args );
    }



    public static function all() : array {
        $stmt = Query::prepare('
            SELECT id, name, breedId, aoc
            FROM color
            ORDER BY name;
        ');
        return Query::selectArray($stmt );
    }

}