<?php

namespace App\model;


use App\util\Query;

class Pair {
	public static function read($id) : ? array {
		$args = get_defined_vars();
		$stmt = Query::prepare('
		SELECT id, breederId, districtId, year, `group`, sectionId, breedId, colorId, name, paired, notes, accepted
		FROM pair
		WHERE id=:id
	');
		return Query::select($stmt, $args);
	}

	public static function create(int $breederId, int $districtId, int $year, string $group, int $sectionId, int $breedId, ?int $colorId, string $name, ?string $paired, ?string $notes, int $accepted, int $modifierId): ?int
	{
		$args = get_defined_vars();
		$stmt = Query::prepare('
		INSERT INTO pair ( breederId, districtId, year, `group`, sectionId, breedId, colorId, name, paired, notes, accepted, modifierId ) 
		VALUES ( :breederId, :districtId, :year, :group, :sectionId, :breedId, :colorId, :name, :paired, :notes, :accepted, :modifierId )
	');
		return Query::insert($stmt, $args);
	}


	public static function update( int $id, int $breederId, int $districtId, int $year, string $group, int $sectionId, int $breedId, ?int $colorId, string $name, ?string $paired, ?string $notes, int $accepted, int $modifierId): bool
	{
		$args = get_defined_vars();
		$stmt = Query::prepare('
			UPDATE  pair
			SET breederId=:breederId, districtId=:districtId, year=:year, `group`=:group, sectionId=:sectionId, breedId=:breedId, colorId=:colorId, name=:name, paired=:paired, notes=:notes, accepted=:accepted, modifierId=:modifierId
			WHERE id=:id
		');
		return Query::update($stmt, $args);
	}


	public static function delete(int $id): bool
	{
		$args = get_defined_vars();
		$stmt = Query::prepare('
			DELETE 
			FROM pair
			WHERE id=:id
		');
		return Query::delete($stmt, $args);
	}


	// to check before breed delete
	public static function readForBreed(int $breedId) : array {
		$args = get_defined_vars();
		$stmt = Query::prepare('
			SELECT id, breedId
			FROM pair
			WHERE breedId=:breedId
		');
		return Query::selectArray($stmt, $args);
	}

	// as check before color delete
	public static function readForColor(int $colorId) : array {
		$args = get_defined_vars();
		$stmt = Query::prepare('
			SELECT id, colorId
			FROM pair
			WHERE colorId=:colorId
		');
		return Query::selectArray($stmt, $args);
	}





	// for breeder his pairs, all
	public static function readForBreeder(int $breederId) : array
	{
		$args = get_defined_vars();
		$stmt = Query::prepare('
		SELECT pair.id, pair.year, pair.group, pair.districtId, pair.breederId, 
			user.firstname, user.infix, user.lastname, user.member, 
			pair.name,
			pair.sectionId,  
			pair.breedId, breed.name AS breedName, breed.layEggs AS layEggsShould, breed.layWeight AS layWeightShould, breed.broodGroup AS broodGroup, 
			pair.colorId, color.name AS colorName,
			result.layEggs, result.layWeight, result.broodEggs, result.broodFertile, result.broodHatched, 
			result.showCount, result.showScore,
			pair.accepted
		FROM pair
		LEFT JOIN breed ON breed.id = pair.breedId
		LEFT JOIN color ON color.id = pair.colorId
		LEFT JOIN result ON result.pairId = pair.id
		LEFT JOIN user ON user.id = pair.breederId
		WHERE pair.breederId=:breederId
		ORDER BY pair.year DESC, breed.name, color.name, pair.name
	');
		return Query::selectArray($stmt, $args);
	}


	// for finding parents pair
	public static function readForBreederBreedYear(int $breederId, int $breedId, int $year) : array
	{ // TODO move to pair!
		$args = get_defined_vars();
		$stmt = Query::prepare('
		SELECT pair.id, pair.year, pair.group, pair.districtId, pair.breederId, 
			user.firstname, user.infix, user.lastname, user.member, 
			pair.sectionId, pair.breedId, pair.colorId, pair.name, 
			breed.name AS breedName, breed.layEggs AS layEggsShould, breed.layWeight AS layWeightShould, breed.broodGroup AS broodGroup, 
			color.name AS colorName,
			result.layEggs, result.layWeight, result.broodEggs, result.broodFertile, result.broodHatched, result.showCount, result.showScore,
			pair.accepted
		FROM pair
		LEFT JOIN breed ON breed.id = pair.breedId
		LEFT JOIN color ON color.id = pair.colorId
		LEFT JOIN result ON result.pairId = pair.id
		LEFT JOIN user ON user.id = pair.breederId
		WHERE pair.breederId=:breederId
		  AND pair.breedId=:breedId
		  AND pair.year=:year
		ORDER BY pair.name
	');
		return Query::selectArray($stmt, $args);
	}

	public static function readForDistrictInYear(int $districtId, int $year) : array
	{ // TODO move to pair!
		$args = get_defined_vars();
		$stmt = Query::prepare('
		SELECT pair.id, pair.year, pair.group, pair.districtId,
			user.id AS breederId, user.firstname, user.infix, user.lastname, user.member,    
			pair.sectionId, pair.breedId, pair.colorId, pair.name, breed.name AS breedName, color.name AS colorName,
			result.layEggs, result.layWeight, result.broodEggs, result.broodFertile, result.broodHatched, result.showCount, result.showScore
		FROM pair
		LEFT JOIN breed ON breed.id = pair.breedId
		LEFT JOIN color ON color.id = pair.colorId
		LEFT JOIN result ON result.pairId = pair.id
		LEFT JOIN user ON user.id = pair.breederId
		WHERE pair.districtId=:districtId AND pair.year=:year
		ORDER BY pair.year DESC, pair.name
	');
		return Query::selectArray($stmt, $args);
	}

	public static function findForChick( string $chick ) : ? array
	{
		$args = get_defined_vars();
		$stmt = Query::prepare('
			SELECT pair.id, pair.year, pair.group, pair.districtId,	pair.breederId,	pair.accepted,    
				pair.sectionId, pair.breedId, pair.colorId, pair.name, 
				result.layEggs, result.layWeight, result.broodEggs, result.broodFertile, result.broodHatched, result.showCount, result.showScore,
				breed.layEggs AS layEggsShould, breed.layWeight AS layWeightShould, breed.broodGroup AS broodGroup
			FROM pair 
			LEFT JOIN pair_chick ON pair_chick.pairId = pair.id
			LEFT JOIN result     ON result.pairId = pair.id
			LEFT JOIN breed      ON breed.id = pair.breedId
			WHERE pair_chick.ring=:chick
		');
		return Query::select($stmt, $args); // returns pair of null
	}

}
