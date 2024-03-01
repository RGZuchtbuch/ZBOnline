<?php

namespace App\model;

use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;

class Animal
{
	public static function get( int $id = null ) : ? array {
		if( $id ) { // one
			$args = get_defined_vars();
			$stmt = Query::prepare('
				SELECT id, year, sex, ring, score, pairId, broodId
				FROM animal
				WHERE id=:id
			');
			return Query::select($stmt, $args);
		} else { //all
			$stmt = Query::prepare('
				SELECT id, year, sex, ring, score, pairId, broodId
				FROM animal
				ORDER BY year, ring
			');
			return Query::selectArray($stmt );
		}
	}

	public static function new( ? string $sex, string $ring, ? int $pairId, ? int $broodId, int $modifierId ) : ? int {
		$args = get_defined_vars();
		$stmt = Query::prepare( '
            INSERT INTO animal ( year, sex, ring, score, pairId, broodId, modifierId )
            VALUES ( :year, :sex, :ring, :score, :pairId, :broodId, :modifierId )
        ' );
		return Query::insert( $stmt, $args );
	}

    public static function set( int $id,   int $year, ? string $sex, string $ring, ? int $score, ? int $pairId, ? int $broodId, int $modifierId ) : bool {
		$args = get_defined_vars();
        $stmt = Query::prepare( '
            UPDATE animal
            SET year=:year, sex=:sex, ring=:ring, score=:score, pairId=:pairId, broodId=:broodId, modifierId=:modifierId
            WHERE id=:id
        ' );
        return Query::update( $stmt, $args );
    }

    public static function del( int $id ) : bool {
		$args = get_defined_vars();
		$stmt = Query::prepare( '
            DELETE FROM animal WHERE id=:id
        ' );
		return Query::delete( $stmt, $args );
    }
}
