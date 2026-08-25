<?php

namespace App\model;

use App\util\Query;

class Result
{
    public static function get( $id ) : ? array {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            SELECT id, pairId, districtId, `year`, `group`, sectionId, breedId, colorId, aocColor, breeders, pairs, layDames, layEggs, broodEggs, broodFertile, broodHatched, showCount, showScore
            FROM result
            WHERE id=:id
        ' );
        return Query::select( $stmt, $args );
    }

    public static function new(
        ? int $pairId, ? int $breedingId, int $districtId, int $year, string $group,
        ? int $sectionId, int $breedId, ? int $colorId, ? string $aocColor,
        int $breeders, ? int $pairs,
        ? int $layDames, ? float $layEggs, ? float $layWeight,
        ? int $broodEggs, ? int $broodFertile, ? int $broodHatched,
        ? int $showCount, ? float $showScore,
        int $modifierId
    ) : ? int {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            INSERT INTO result (
                pairId, breedingId, districtId, `year`, `group`,
                sectionId, breedId, colorId, aocColor,
                breeders, pairs,
                layDames, layEggs, layWeight, broodEggs, broodFertile, broodHatched, showCount, showScore, modifierId
            )
            VALUES ( :pairId, :breedingId, :districtId, :year, :group, :sectionId, :breedId, :colorId, :aocColor, :breeders, :pairs, :layDames, :layEggs, :layWeight, :broodEggs, :broodFertile, :broodHatched, :showCount, :showScore, :modifierId )
        ' );
        return Query::insert( $stmt, $args );
    }


    public static function set(
        int $id, ? int $pairId, ? int $breedingId, int $districtId, int $year, string $group,
		? int $sectionId, int $breedId, ? int $colorId, ? string $aocColor,
        int $breeders, ? int $pairs,
        ? int $layDames, ? float $layEggs, ? float $layWeight,
        ? int $broodEggs, ? int $broodFertile, ? int $broodHatched,
        ? int $showCount, ? float $showScore,
        int $modifierId
    ) : bool {
        $args = get_defined_vars();
//        print_r( $args );
//        return false;
        $stmt = Query::prepare( '
            UPDATE  result
            SET pairId=:pairId, breedingId=:breedingId, districtId=:districtId, `year`=:year, `group`=:group,
                sectionId=:sectionId, breedId=:breedId, colorId=:colorId, aocColor=:aocColor,
                breeders=:breeders, pairs=:pairs,
                layDames=:layDames, layEggs=:layEggs, layWeight=:layWeight,
                broodEggs=:broodEggs, broodFertile=:broodFertile, broodHatched=:broodHatched,
                showCount=:showCount, showScore=:showScore,
                modifierId=:modifierId
            WHERE id=:id
        ' );
        return Query::update( $stmt, $args );
    }

    public static function delete(int $id ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            DELETE 
            FROM result
            WHERE id=:id
        ' );
        return Query::delete( $stmt, $args );
    }

    public static function deleteForpair( int $pairId ) { // delete result on pair delete
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            DELETE 
            FROM result
            WHERE pairId=:pairId
        ' );
        return Query::delete( $stmt, $args );
    }

	public static function readForPair(int $pairId): ? array // may not be there
	{
		$args = get_defined_vars();
		$stmt = Query::prepare(" 
			SELECT pair.id, pair.breederId, pair.year, pair.name, 
				layEggs, layWeight, 
				broodEggs, broodFertile, broodHatched,
				showCount, showScore
			FROM pair
			LEFT JOIN result ON result.pairId = pair.id
			WHERE pair.id = :pairId
		");
		return Query::select($stmt, $args);
	}

    /**
     * REPORTS, the bigguns
     */

	// for checking before deleting breed that might have results or pairs yet
    public static function getAllWithBreed(int $id ) : array {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            SELECT id, breedId
            FROM result
            WHERE breedId=:id
        ' );
        return Query::selectArray( $stmt, $args );
    }

	// for checking before deleting color that might have results or pairs
    public static function getAllWithColor(int $id ) : array {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            SELECT id, colorId
            FROM result
            WHERE colorId=:id
        ' );
        return Query::selectArray( $stmt, $args );
    }

	// new version 2 getters, for moderator result view
	public static function forDistrictYear( int $districtId, int $year ) : array {
		$args = get_defined_vars();
		$stmt = Query::prepare('
			SELECT
				result.id, result.year, result.pairId, result.breedingId, result.districtId,
					result.group, result.breeders, result.pairs, 
					result.breedId, breed.name AS breedname, result.colorId, color.name AS colorname, result.aocColor,		
					result.layDames, result.layEggs, result.layWeight,
					result.broodEggs, result.broodFertile, result.broodHatched,
					result.showCount, result.showScore,				
				supersection.id AS supersectionId, supersection.name AS supersectionname,
				rootsection.id AS rootsectionId, rootsection.name AS rootsectionname,
				section.id AS sectionId, section.name AS sectionname,
				user.id AS breederId, user.firstname, user.infix, user.lastname,
				CONCAT(SUBSTR(user.firstname, 1, 1), ".", SUBSTR(user.lastname, 1, 1) ) AS short,
				pair.accepted			
			FROM result
				LEFT JOIN breed  ON breed.id = result.breedId
				LEFT JOIN color  ON color.id = result.colorId
				LEFT JOIN section ON section.id = breed.sectionId
				LEFT JOIN section AS rootsection ON rootsection.id = section.rootId
				LEFT JOIN section AS supersection ON supersection.id = section.parentId
				LEFT JOIN pair   ON pair.id = result.pairId
				LEFT JOIN user   ON user.id = pair.breederId OR user.id = result.breedingId
			WHERE result.districtId = :districtId AND result.year = :year
			ORDER BY rootsection.order, breed.name, result.aocColor, color.name, pairId, breedingId
        ');
		return Query::selectArray($stmt, $args);
	}
//    public static function forDistrictYear( int $districtId, int $year ) : array {
//		$args = get_defined_vars();
//		$stmt = Query::prepare('
//			SELECT
//				result.id, result.year, result.pairId, result.breedingId, result.districtId,
//					result.group, result.breeders, result.pairs,
//					result.breedId, breed.name AS breedname, result.colorId, color.name AS colorname, result.aocColor,
//					result.layDames, result.layEggs, result.layWeight,
//					result.broodEggs, result.broodFertile, result.broodHatched,
//					result.showCount, result.showScore,
//				supersection.id AS supersectionId, supersection.name AS supersectionname,
//				rootsection.id AS rootsectionId, rootsection.name AS rootsectionname,
//				section.id AS sectionId, section.name AS sectionname,
//				user.id AS breederId, user.firstname, user.infix, user.lastname,
//				CONCAT(SUBSTR(user.firstname, 1, 1), ".", SUBSTR(user.lastname, 1, 1) ) AS short,
//				pair.accepted
//			FROM result
//				LEFT JOIN breed  ON breed.id = result.breedId
//				LEFT JOIN color  ON color.id = result.colorId
//				LEFT JOIN section ON section.id = breed.sectionId
//				LEFT JOIN section AS rootsection ON rootsection.id = section.rootId
//				LEFT JOIN section AS supersection ON supersection.id = section.parentId
//				LEFT JOIN pair   ON pair.id = result.pairId
//				LEFT JOIN user   ON user.id = pair.breederId OR user.id = result.breedingId
//			WHERE result.districtId = :districtId AND result.year = :year
//			ORDER BY rootsection.order, breed.name, result.aocColor, color.name
//        ');
//		return Query::selectArray($stmt, $args);
//	}
	/* returns the district result for year and color, for moderator overview per year */
    // is this one used anywhere ?
	public static function forDistrict( int $districtId ) : array {
		$args = get_defined_vars();
		$stmt = Query::prepare('
			SELECT
				result.id, result.year, result.pairId, result.districtId, 
					result.group, result.breeders, result.pairs, 
					result.breedId, breed.name AS breedname, result.colorId, color.name AS colorname, result.aocColor,		
					result.layDames, result.layEggs, result.layWeight,
					result.broodEggs, result.broodFertile, result.broodHatched,
					result.showCount, result.showScore,				
				supersection.id AS supersectionId, supersection.name AS supersectionname,
				rootsection.id AS rootsectionId, rootsection.name AS rootsectionname,
				section.id AS sectionId, section.name AS sectionname,
				user.id AS breederId, user.firstname, user.infix, user.lastname,
				CONCAT(SUBSTR(user.firstname, 1, 1), ".", SUBSTR(user.lastname, 1, 1) ) AS short
			FROM result
				LEFT JOIN breed  ON breed.id = result.breedId
				LEFT JOIN color  ON color.id = result.colorId
				LEFT JOIN section ON section.id = breed.sectionId
				LEFT JOIN section AS rootsection ON rootsection.id = section.rootId
				LEFT JOIN section AS supersection ON supersection.id = section.parentId
				LEFT JOIN pair   ON pair.id = result.pairId
				LEFT JOIN user   ON user.id = pair.breederId		
			WHERE result.districtId = :districtId
			ORDER BY result.`year` DESC, section.order, breed.name, color.name 
        ');
		return Query::selectArray($stmt, $args);
	}

	/* returns the district result for year and color */
    // used ?
	public static function forColor( int $districtId, int $year, int $colorId ) : array {
		$args = get_defined_vars();
		$stmt = Query::prepare('
			SELECT * FROM result WHERE districtId=:districtId AND `year`=:year AND colorId=:colorId ORDER BY pairId
        ');
		return Query::selectArray($stmt, $args);
	}

	// for editing district results per section, year, group
	public static function forSectionBreeds(int $districtId, int $sectionId, int $year, string $group ) : array {
		$args = get_defined_vars();

		$stmt = Query::prepare("
			SELECT breed.id, breed.name, section.layers AS layer, COUNT( result.id ) AS count
			FROM breed
			LEFT JOIN section ON section.id = breed.sectionId
			LEFT JOIN result ON  result.breedId = breed.id AND result.districtId = :districtId AND result.year = :year AND result.group = :group
			WHERE
				section.rootId = :sectionId
#				AND result.pairId IS NULL
#				AND result.aocColor IS NULL
			GROUP BY breed.name
			ORDER BY breed.name	
        ");
		return Query::selectArray( $stmt, $args );
	}

	public static function forDistrictBreedResult(int $districtId, int $breedId, int $year, string $group ) : array {
		$args = get_defined_vars();

		$stmt = Query::prepare("
			SELECT 
				:breedId AS breedId, :districtId AS districtId, :year AS `year`, :group AS `group`,
				result.id AS id,
				section.rootId AS sectionId, null AS colorId, null AS colorName,
				pairId, breeders, pairs, aocColor, 
				result.layDames, result.layEggs, result.layWeight,
				result.broodEggs, result.broodFertile, result.broodHatched,
				result.showCount, result.showScore
			FROM breed
			    LEFT JOIN section ON section.id = breed.sectionId
				LEFT JOIN result 
					ON result.breedId = breed.id
					AND result.colorId IS NULL
					AND result.districtId = :districtId
					AND result.year = :year
					AND result.group = :group
					AND result.pairId IS NULL
					AND result.breedingId IS NULL
					AND result.aocColor IS NULL
			WHERE 
				breed.id = :breedId	
        ");
		return Query::select( $stmt, $args );
	}

	public static function forDistrictColorsResult(int $districtId, int $breedId, int $year, string $group ) : array {
		$args = get_defined_vars();

		$stmt = Query::prepare("
			SELECT
				section.rootId AS sectionId, breed.id AS breedId, color.id AS colorId, color.name AS colorName,
				:districtId AS districtId, :year AS `year`,
				result.id, pairId, :group AS `group`,
				aocColor, breeders, pairs,
				result.layDames, result.layEggs, result.layWeight,
				result.broodEggs, result.broodFertile, result.broodHatched,
				result.showCount, result.showScore
			FROM color
			    LEFT JOIN breed ON breed.id = color.breedId
			    LEFT JOIN section ON section.id = breed.sectionId
				LEFT JOIN result
					ON result.colorId = color.id
					AND result.districtId = :districtId
					AND result.year = :year
					AND result.group = :group
					AND result.pairId IS NULL
					AND result.breedingId IS NULL
					AND result.aocColor IS NULL
			WHERE
				color.breedId = :breedId
			ORDER BY color.name
        ");
		return Query::selectArray( $stmt, $args );
	}

	public static function forDistrictAocsResult(int $districtId, int $breedId, int $year, string $group ) : array {
		$args = get_defined_vars();

		$stmt = Query::prepare("
			SELECT 
				section.rootId AS sectionId, result.breedId AS breedId, null AS colorId, null AS colorName,
				result.districtId AS districtId, :year AS `year`,
				result.id, pairId, :group AS `group`, 
				aocColor, breeders, pairs, 
				result.layDames, result.layEggs, result.layWeight,
				result.broodEggs, result.broodFertile, result.broodHatched,
				result.showCount, result.showScore
			FROM result
			    LEFT JOIN breed ON breed.id = result.breedId
			    LEFT JOIN section ON section.id = breed.sectionId
			WHERE
			    result.aocColor IS NOT NULL
			    AND result.districtId=:districtId
			    AND result.breedId=:breedId
			    AND result.group=:group
			    AND result.year=:year
				AND result.pairId IS NULL
				AND result.breedingId IS NULL
				# AND result.colorId IS NULL
			ORDER BY result.aocColor		
        ");
		return Query::selectArray( $stmt, $args );
	}

	// for editing district results per section, year, group special for aoc 'section'
	public static function forDistrictAocs(int $districtId, int $year, string $group ) : array {
		$args = get_defined_vars();

		$stmt = Query::prepare("
            SELECT 
          		result.id, result.pairId, :districtId AS districtId, :year AS `year`, :group AS `group`, 
                result.sectionId, section.name AS sectionName, section.layers,
          		breed.id AS breedId, breed.name AS breedName, null AS colorId, result.aocColor, result.aocColor AS colorName, result.aocColor AS name,
                breeders, pairs,
                layDames, result.layEggs, result.layWeight,
                result.broodEggs, result.broodFertile, result.broodHatched,
                result.showCount, result.showScore
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

public static function forDistrictBreeders( int $districtId, int $year ) : array {
		$args = get_defined_vars();

		$stmt = Query::prepare("
            SELECT
            	user.id AS breederId, user.firstname, user.infix, user.lastname, user.member, user.club,
            	result.id AS resultId, result.pairId, result.breedingId, result.districtId, result.year, result.group, result.breeders, result.pairs,
            	section.rootId AS sectionId,                               # note, not subsection !
            	breed.id AS breedId, breed.name AS breedname,
            	color.id AS colorId, color.name AS colorname, result.aocColor,

            	result.layDames, result.layEggs, result.layWeight,
            	result.broodEggs, result.broodFertile, result.broodHatched,
            	result.showCount, result.showScore

            FROM user
            	LEFT JOIN result  ON result.breedingId = user.id AND result.year = :year
            	LEFT JOIN color   ON color.id   = result.colorId
            	LEFT JOIN breed   ON breed.id   = result.breedId
            	LEFT JOIN section ON section.id = breed.sectionId

            WHERE user.districtId = :districtId
            ORDER BY user.lastname, user.firstname, user.infix, section.order, breed.name, color.name, result.aocColor
        ");

		return Query::selectArray( $stmt, $args );
	}


}