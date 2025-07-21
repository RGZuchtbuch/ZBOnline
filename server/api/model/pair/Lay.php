<?php

namespace App\model\pair;

use App\util\Query;

class Lay {
	public static function readForPair($pairId): ?array
	{
		$args = get_defined_vars();
		$stmt = Query::prepare('
			SELECT id, pairId, start, end, eggs, dames, weight
			FROM pair_lay
			WHERE pairId=:pairId
		');
		return Query::select($stmt, $args);
	}

	public static function create(int $pairId, string $start, string $end, int $eggs, ?int $dames, ?int $weight, int $modifierId): int
	{
		$args = get_defined_vars();
		$stmt = Query::prepare('
			INSERT INTO pair_lay ( pairId, start, end, eggs, dames, weight, modifierId ) 
			VALUES ( :pairId, :start, :end, :eggs, :dames, :weight, :modifierId )
		');
		return Query::insert($stmt, $args);
	}

	public static function update(int $id, int $pairId, string $start, string $end, int $eggs, ?int $dames, ?int $weight, int $modifierId): bool
	{
		$args = get_defined_vars();
		$stmt = Query::prepare('
			UPDATE pair_lay
			SET pairId=:pairId, start=:start, end=:end, eggs=:eggs, dames=:dames, weight=:weight, modifierId=:modifierId
			WHERE id=:id
		');
		return Query::update($stmt, $args);
	}

	public static function delete(int $id): bool {
		$args = get_defined_vars();
		$stmt = Query::prepare('
			DELETE 
			FROM pair_lay
			WHERE id=:id
		');
		return Query::delete($stmt, $args);
	}

	public static function deleteForPair(int $pairId): bool
	{
		$args = get_defined_vars();
		$stmt = Query::prepare('
			DELETE
			FROM pair_lay
			WHERE pairId=:pairId
		');
		return Query::delete($stmt, $args);
	}

}