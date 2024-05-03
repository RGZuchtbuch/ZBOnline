<?php

namespace App\model;

use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpNotImplementedException;

class District
{
	public static function get( int $id = null ) : ? array {
		if( $id ) {
			$args = get_defined_vars();
			$stmt = Query::prepare( '
				SELECT district.* 
				FROM district
				WHERE id=:id
			' );
			return Query::select($stmt, $args);
		} else {
			$stmt = Query::prepare( '
				SELECT id, parentId, name, fullname, short, level, latitude, longitude, moderatorId  
				FROM district
				ORDER BY name
			' );
			return Query::selectArray($stmt );
		}
    }

	public static function new(
		int $parentId, string $name, string $fullname, string $short, // parentId cannot change
		? float $latitude, ? float $longitude,
		string $level, ? int $moderatorId, int $modifierId
	) : ? int {
		$args = get_defined_vars();
		$stmt = Query::prepare( '
            INSERT INTO district ( parentId, name, fullname, short, latitude, longitude, level, moderatorId, modifierId )
            VALUES ( :parentId, :name, :fullname, :short, :latitude, :longitude, :level, :moderatorId, :modifierId )
        ' );
		return Query::insert( $stmt, $args );
	}

    public static function set(
        int $id,
        string $name, ? string $fullname, ? string $short,
        ? float $latitude, ? float $longitude,
        string $level, ? int $moderatorId, int $modifierId
    ) : bool {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            UPDATE district
            SET name=:name, fullname=:fullname, short=:short, latitude=:latitude, longitude=:longitude, level=:level, moderatorId=:moderatorId, modifierId=:modifierId
            WHERE id=:id  
        ');
        return Query::update($stmt, $args);
    }
    public static function del( int $id ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            DELETE 
            FROM district
            WHERE id=:id
        ' );
        return Query::delete( $stmt, $args );
    }






    /**
     * @param int $districtId
     * @return array of breeders in district incl descendants
     */
    public static function getBreeders(int $districtId ) : array {
        $args = get_defined_vars();
        $stmt = Query::prepare( "
            SELECT 
                breeder.id, breeder.firstname, breeder.infix, breeder.lastname, 
                district.id AS districtId, district.name AS districtName,
                club.id AS clubId, club.name AS clubName, start, end
			FROM user AS breeder
                LEFT JOIN district ON district.id=breeder.districtId 
                LEFT JOIN district AS club ON club.id=breeder.clubId 
            WHERE breeder.districtId IN (
                SELECT DISTINCT child.id FROM district AS parent
                    LEFT JOIN district AS child ON child.id = parent.id OR child.parentId = parent.id
                WHERE parent.id=:districtId OR parent.parentId = :districtId  
            )
            ORDER BY breeder.lastName, breeder.firstName
        ");
        return Query::selectArray( $stmt, $args );
    }

    // select children
    public static function getChildren(int $parentId ) : array {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            SELECT id, parentId, name, level
            FROM district 
            WHERE parentId=:parentId
            ORDER BY name
        ');
        return Query::selectArray( $stmt, $args );
    }

    // select root and 2 level descendants
    public static function getDescendants(int $districtId ) : array {
        $args = get_defined_vars();
        $stmt = Query::prepare( "
            SELECT DISTINCT child.parentId, child.id, child.short, child.name, child.fullname, child.level, child.moderatorId 
            FROM district AS parent
            LEFT JOIN district AS child ON child.id = parent.id OR child.parentId = parent.id
            WHERE parent.id=:districtId OR parent.parentId=:districtId
            ORDER BY name
        ");
        return Query::selectArray( $stmt, $args );
    }

    /*
     * getting results for pigeons for results edit
     */
    public static function getBreedResult(int $districtId, int $breedId, int $year, string $group ) : array {
        $args = get_defined_vars();
        $stmt = Query::prepare("
            SELECT 
                   result.id, result.pairId, :districtId AS districtId, :year AS `year`, :group AS `group`,             
                   breed.id AS breedId, null AS colorId, 'Gesamte Farbenschläge' AS colorName, 'Gesamte Farbenschläge' AS name,  
                   result.breeders, result.pairs,
                   result.layDames, result.layEggs, result.layWeight,
                   result.broodEggs, result.broodFertile, result.broodHatched,
                   result.showCount, result.showScore
            FROM breed
            LEFT JOIN result ON result.breedId = breed.id AND result.colorId IS NULL AND result.aocColor IS NULL AND result.pairId IS NULL
                AND result.districtId = :districtId
                AND result.year = :year
                AND result.group = :group  
            WHERE breed.id = :breedId
        ");
        return Query::selectArray( $stmt, $args );
    }

    /*
     * get result for a breed's colors, so for non pigeons edit
     */
    public static function getColorResults(int $districtId, int $breedId, int $year, string $group ) : array {
        $args = get_defined_vars();
        $stmt = Query::prepare("
            SELECT result.id, result.pairId, :districtId AS districtId, :year AS `year`, :group AS `group`,
                   breed.id AS breedId, color.id AS colorId, color.name AS colorName, color.name, result.aocColor,
                   result.breeders, result.pairs,
                   result.layDames, result.layEggs, result.layWeight,
                   result.broodEggs, result.broodFertile, result.broodHatched,
                   result.showCount, result.showScore
            FROM breed
            LEFT JOIN color ON color.breedId = breed.id    
            LEFT JOIN result ON result.breedId = breed.id AND result.colorId = color.id AND result.pairId IS NULL
                AND result.districtId = :districtId
                AND result.year = :year
                AND result.group = :group             
            WHERE breed.id = :breedId
            ORDER BY color.name 
        ");
        return Query::selectArray( $stmt, $args );
    }

    public static function getSectionResults(int $districtId, int $sectionId, int $year, string $group ) : array {
        $args = get_defined_vars();

        $stmt = Query::prepare("
            SELECT 
          		breed.id, breed.name, 
          		COUNT( result.id ) AS results, :districtId AS districtId, :year AS `year`, :group AS `group`, 
                SUM( breeders ) AS breeders, SUM( pairs ) AS pairs,
                SUM( result.layDames ) AS layDames, AVG( result.layEggs ) AS layEggs, AVG( result.layWeight ) AS layWeight,
                SUM( result.broodEggs) AS broodEggs, SUM( broodFertile ) AS broodFertile, SUM( broodHatched ) AS broodHatched,
                SUM( result.showCount ) AS showCount, AVG( showScore ) AS showScore
            FROM breed
            LEFT JOIN result 
                ON result.breedId = breed.id AND result.pairId IS NULL
                AND result.districtId = :districtId
                AND result.year = :year
                AND result.group = :group
            WHERE breed.sectionId IN (              
				SELECT child.id FROM section AS parent
					LEFT JOIN section AS child ON child.id = parent.id OR child.parentId = parent.id
				WHERE parent.id=:sectionId OR parent.parentId=:sectionId
            )
            GROUP BY breed.id, breed.name
            ORDER BY breed.name 
        ");

        return Query::selectArray( $stmt, $args );
    }

	// gets all results for all sections for aoc, special case
	public static function getAocResults(int $districtId, int $year, string $group ) : array {
		$args = get_defined_vars();

		$stmt = Query::prepare("
            SELECT 
          		result.id, result.pairId, :districtId AS districtId, :year AS `year`, :group AS `group`, 
                result.sectionId, section.name AS sectionName, section.layers,
          		breed.id AS breedId, breed.name AS breedName, null AS colorId, result.aocColor, result.aocColor AS colorName, result.aocColor AS name,
                breeders, pairs,
                layDames, result.layEggs, result.layWeight,
                broodEggs, broodFertile, broodHatched,
                showCount, showScore
            FROM result
            LEFT JOIN breed   ON breed.id = result.breedId
            LEFT JOIN section ON section.id = result.sectionId
            
            WHERE districtId = :districtId
                AND year =:year
                AND `group` = :group
                AND result.aocColor IS NOT NULL
            ORDER BY section.order, breed.name, aocColor
        ");

		return Query::selectArray( $stmt, $args );
	}

    public static function getCountBreeders(int $districtId ) : int {
        $args = get_defined_vars();
        $stmt = Query::prepare("
            SELECT COUNT(*) AS count
            FROM user
            WHERE districtId = :districtId OR clubId = :districtId              
        ");
        $row = Query::select( $stmt, $args );
        if( $row ) {
            return $row['count'];
        }
        return 0;
    }

    public static function getCountChildren(int $districtId ) : int {
        $args = get_defined_vars();
        $stmt = Query::prepare("
            SELECT COUNT(*) AS count
            FROM district
            WHERE parentId = :districtId
        ");
        $row = Query::select( $stmt, $args );
        if( $row ) {
            return $row['count'];
        }
        return 0;
    }

    public static function getCountResults(int $districtId ) : int {
        $args = get_defined_vars();
        $stmt = Query::prepare("
            SELECT COUNT(*) AS count
            FROM result
            WHERE districtId = :districtId
        ");
        $row = Query::select( $stmt, $args );
        if( $row ) {
            return $row['count'];
        }
        return 0;
    }
}
