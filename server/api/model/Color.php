<?php

namespace App\model;

class Color
{

	public static function get( int $id = null ) : ? array {
		if( $id ) { // index
			$args = get_defined_vars();
			$stmt = Query::prepare('
				SELECT id, name, breedId, info
				FROM color
				WHERE id=:id
			');
			return Query::select($stmt, $args);
		} else { // by id
			$stmt = Query::prepare('
				SELECT id, name, breedId
				FROM color
				ORDER BY name;
			');
			return Query::selectArray($stmt );
		}
	}

    public static function new( string $name, int $breedId, ? string $info, int $modifierId ) : ? int {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
        INSERT INTO color ( name, breedId, info, modifierId ) 
            VALUES ( :name, :breedId, :info, :modifierId )
        ' );
        return Query::insert( $stmt, $args );
    }
    public static function set( int $id, string $name, ? string $info, int $modifierId ) : ? int { // cannot change breed
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            UPDATE color
            SET name=:name, info=:info, modifierId=:modifierId
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





}