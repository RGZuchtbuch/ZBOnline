<?php

namespace App\model\pair;

use App\model\Query;

class Brood {
	public static function readForPair($pairId): ? array {
		$args = get_defined_vars();
		$stmt = Query::prepare('
			SELECT id, pairId, start, eggs, fertile, hatched
			FROM pair_brood
			WHERE pairId=:pairId
			ORDER BY start
		');
		return Query::selectArray($stmt, $args); // null or one per pair
	}

	public static function create( int $pairId, ?string $start, int $eggs, ?int $fertile, int $hatched, int $modifierId ): ? int {
		$args = get_defined_vars();
		$stmt = Query::prepare('
			INSERT INTO pair_brood ( pairId, start, eggs, fertile, hatched, modifierId ) 
			VALUES ( :pairId, :start, :eggs, :fertile, :hatched, :modifierId )
		');
		return Query::insert($stmt, $args);
	}


	public static function update( int $id, int $pairId, ?string $start, int $eggs, ?int $fertile, int $hatched, int $modifierId ) : bool {
		$args = get_defined_vars();
		$stmt = Query::prepare('
			UPDATE pair_brood
			SET pairId=:pairId, start=:start, eggs=:eggs, fertile=:fertile, hatched=:hatched, modifierId=:modifierId
			WHERE id=:id
		');
		return Query::update($stmt, $args);
	}

	public static function delete(int $id ): bool {
		$args = get_defined_vars();
		$stmt = Query::prepare('
			DELETE 
			FROM pair_brood
			WHERE id=:id
		');
		return Query::delete($stmt, $args);
	}

	public static function deleteForPair(int $pairId ): bool {
		$args = get_defined_vars();
		$stmt = Query::prepare('
			DELETE 
			FROM pair_brood
			WHERE pairId=:pairId
		');
		return Query::delete($stmt, $args);
	}
}