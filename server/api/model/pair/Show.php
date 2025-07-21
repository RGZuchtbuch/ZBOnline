<?php

namespace App\model\pair;

use App\util\Query;

class Show {
	//*** Lay ***//


	//*** Show ***//
	public static function readForPair($pairId): ? array {
		$args = get_defined_vars();
		$stmt = Query::prepare('
			SELECT id, pairId, `89`, `90`, `91`, `92`, `93`, `94`, `95`, `96`, `97`
			FROM pair_show
			WHERE pairId=:pairId
		');
		return Query::select($stmt, $args); // null or one per pair
	}

	public static function create( int $pairId, ?int $p89, ?int $p90, ?int $p91, ?int $p92, ?int $p93, ?int $p94, ?int $p95, ?int $p96, ?int $p97, int $modifierId ): ? int {
		$args = get_defined_vars();
		$stmt = Query::prepare('
			INSERT INTO pair_show ( pairId, `89`, `90`, `91`, `92`, `93`, `94`, `95`, `96`, `97`, modifierId ) 
			VALUES ( :pairId, :p89, :p90, :p91, :p92, :p93, :p94, :p95, :p96, :p97, :modifierId )
		');
		return Query::insert($stmt, $args);
	}


	public static function update(int $id, int $pairId, ?int $p89, ?int $p90, ?int $p91, ?int $p92, ?int $p93, ?int $p94, ?int $p95, ?int $p96, ?int $p97, int $modifierId ) : bool {
		$args = get_defined_vars();
		$stmt = Query::prepare('
			UPDATE pair_show
			SET pairId=:pairId, `89`=:p89, `90`=:p90, `91`=:p91, `92`=:p92, `93`=:p93, `94`=:p94, `95`=:p95, `96`=:p96, `97`=:p97, modifierId=:modifierId
			WHERE id=:id
		');
		return Query::update($stmt, $args);
	}

	public static function deleteForPair(int $pairId ): bool {
		$args = get_defined_vars();
		$stmt = Query::prepare('
			DELETE 
			FROM pair_show
			WHERE pairId=:pairId
		');
		return Query::delete($stmt, $args);
	}
}