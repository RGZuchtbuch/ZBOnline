<?php

namespace App\model;

use App\util\Query;

// Still work in progress, not usefull for now but prep for more aniamal based parents/chicks.
class Animal {

	public static function readParent( int $id ) : ? array {
		$args = get_defined_vars();
		$stmt = Query::prepare('
			SELECT id, pairId, sex, ring, score, parentsPairId
			FROM pair_parent  
			WHERE pairId = :pairId
		');
		return Query::selectArray($stmt, $args);
	}

	public static function readParentsForPair( $pairId ): array {
		$args = get_defined_vars();
		$stmt = Query::prepare('
			SELECT id, pairId, sex, ring, score, parentsPairId
			FROM pair_parent
			WHERE pairId=:pairId
			ORDER BY sex, ring
		');
		return Query::selectArray($stmt, $args);
	}

	public static function createParent(int $pairId, string $sex, string $ring, ?float $score, ?int $parentsPairId, int $modifierId): ? int	{
		$args = get_defined_vars();
		$stmt = Query::prepare('
			INSERT INTO pair_parent ( pairId, sex, ring, score, parentsPairId, modifierId ) 
			VALUES ( :pairId, :sex, :ring, :score, :parentsPairId, :modifierId )
		');
		return Query::insert($stmt, $args);
	}
	public static function updateParent( int $id, int $pairId, string $sex, string $ring, ?float $score, ?int $parentsPairId, int $modifierId): ? int	{
		$args = get_defined_vars();
		$stmt = Query::prepare('
			UPDATE pair_parent
			SET pairId=:pairId, sex=:sex, ring=:ring, score=:score, parentsPairId=:parentsPairId, modifierId=:modifierId
			WHERE id=:id
		');
		return Query::insert($stmt, $args);
	}

	public static function delParent( int $id ): bool
	{
		$args = get_defined_vars();
		$stmt = Query::prepare('
			DELETE 
			FROM pair_parent
			WHERE id=:id
		');
		return Query::delete($stmt, $args);
	}

	public static function deleteParentsForPair(int $pairId): bool
	{
		$args = get_defined_vars();
		$stmt = Query::prepare('
			DELETE 
			FROM pair_parent
			WHERE pairId=:pairId
		');
		return Query::delete($stmt, $args);
	}


/** Chick **/
	//*** Brood chicks ***//

	public static function createChick(int $pairId, ?int $broodId, ?string $ringed, string $ring, int $modifierId): ? int {
		$args = get_defined_vars();
		$stmt = Query::prepare('
			INSERT INTO pair_chick ( pairId, broodId, ringed, ring, modifierId ) 
			VALUES ( :pairId, :broodId, :ringed, :ring, :modifierId )
		');
		return Query::insert($stmt, $args);
	}

	// no set as all renewed at report save

	public static function readForBrood( $broodId ): ? array {
		$args = get_defined_vars();
		$stmt = Query::prepare('
			SELECT id, pairId, broodId, ringed, ring
			FROM pair_chick
			WHERE broodId=:broodId
		');
		return Query::selectArray($stmt, $args);
	}

	public static function deleteChicksForPair(int $pairId) : bool {
		$args = get_defined_vars();
		$stmt = Query::prepare('
			DELETE 
			FROM pair_chick
			WHERE pairId=:pairId
		');
		return Query::delete($stmt, $args);
	}

}