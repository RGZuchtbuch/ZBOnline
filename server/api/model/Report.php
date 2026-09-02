<?php

namespace App\model;

use App\util\Query;

class Report extends Query
{

	/**
	 * REPORTS, the bigguns
	 */

	// for charts, one result for current district and year ( filtered for s,b,c )
	// for 1 district and 1 year, filtered for section, brood, color or group

	public static function forChart(int $districtId, int $year, ? int $sectionId, ? int $breedId, ? int $colorId, ? string $group ) : ? array {
		$args = get_defined_vars();
		$stmt = Query::prepare('
            WITH RECURSIVE
				districts AS (
            		SELECT id FROM district WHERE id = :districtId
            		UNION ALL
            		SELECT child.id FROM district AS child
            		JOIN districts AS parent ON parent.id = child.parentId
            	),
                sections AS (
                    SELECT id FROM section WHERE id = :sectionId
                    UNION ALL
                    SELECT child.id FROM section AS child
                    JOIN sections AS parent ON parent.id = child.parentId
                ),
            	results AS (
            		SELECT
            			SUM( CASE WHEN pairId IS NULL THEN breeders ELSE 0 END ) + COUNT( DISTINCT pair.breederId ) AS breeders,
                        SUM( pairs ) AS pairs,
            			SUM( CASE WHEN result.layEggs > 0 THEN breeders ELSE 0 END ) AS layEggsBreeders,
            			SUM( breeders * result.layEggs ) AS layEggs,
            			SUM( CASE WHEN result.layWeight > 0 THEN breeders ELSE 0 END ) AS layWeightBreeders,
            			SUM( breeders * result.layWeight ) AS layWeight,

						breed.layEggs   AS layEggsShould,
						breed.layWeight AS layWeightShould,

            			SUM( broodEggs ) AS broodEggs,
            			SUM( CASE WHEN broodEggs > 0 AND broodHatched >= 0 THEN breeders ELSE 0 END ) AS broodHatchedBreeders,
            			SUM( CASE WHEN broodEggs > 0 AND broodHatched >= 0 THEN breeders * broodHatched / broodEggs ELSE NULL END ) AS broodHatched,

            			SUM( CASE WHEN section.layers = 1 AND broodEggs > 0 AND broodFertile >= 0 THEN breeders ELSE 0 END ) AS broodLayerFertileBreeders,
            			SUM( CASE WHEN section.layers = 1 AND broodEggs > 0 AND broodFertile >= 0 THEN breeders * broodFertile / broodEggs ELSE NULL END ) AS broodLayerFertile,
            			SUM( CASE WHEN section.layers = 1 AND broodEggs > 0 AND broodHatched >= 0 THEN breeders ELSE 0 END ) AS broodLayerHatchedBreeders,
            			SUM( CASE WHEN section.layers = 1 AND broodEggs > 0 AND broodHatched >= 0 THEN breeders * broodHatched / broodEggs ELSE NULL END ) AS broodLayerHatched,

            			SUM( CASE WHEN section.layers = 0 AND broodEggs > 0 AND broodHatched >= 0 THEN breeders ELSE 0 END ) AS broodPigeonHatchedBreeders,
            			SUM( CASE WHEN section.layers = 0 AND broodEggs > 0 AND broodHatched >= 0 THEN breeders * broodHatched / broodEggs ELSE NULL END ) AS broodPigeonHatched,
            			SUM( CASE WHEN section.layers = 0 AND pairs > 0 AND broodHatched >= 0 THEN breeders ELSE 0 END ) AS broodPigeonResultBreeders,
            			SUM( CASE WHEN section.layers = 0 AND pairs > 0 AND broodHatched >= 0 THEN breeders * broodHatched / pairs ELSE 0 END ) AS broodPigeonResult,

            			SUM( CASE WHEN showCount > 0 THEN breeders ELSE 0 END ) AS showBreeders,
            			SUM( showCount ) AS showCount,
            			SUM( breeders * showScore ) AS showScore,
						section.layers
            		FROM
            			result
            			LEFT JOIN pair  ON pair.id  = result.pairId 			# for breederId
						LEFT JOIN breed ON breed.id = result.breedId    		# for leyEggs and layWeight should
						LEFT JOIN section ON section.id = result.sectionId      # for layers
            		WHERE
            			result.districtId IN ( SELECT id FROM districts )
            			AND result.year = :year
             			AND ( :group IS NULL OR result.group = :group )
                        AND ( :sectionId IS NULL OR result.sectionId IN ( SELECT * FROM sections ) )
                        AND ( :breedId IS NULL OR result.breedId = :breedId )
                        AND ( :colorId IS NULL OR result.colorId = :colorId )
            		GROUP BY result.breedId, result.colorId, result.aocColor
            	)

            SELECT
                :districtId AS districtId, :year AS `year`, :group AS `group`,
            	:sectionId AS sectionId, :breedId AS breedId, :colorId AS colorId,

            	CAST( SUM( layEggsBreeders   * layEggsShould )   / SUM( layEggsBreeders) AS FLOAT )   AS layEggsShould,
            	CAST( SUM( layWeightBreeders * layWeightShould ) / SUM( layWeightBreeders) AS FLOAT ) AS layWeightShould,

            	CAST( SUM( breeders ) AS UNSIGNED ) AS breeders,
                CAST( SUM( pairs ) AS UNSIGNED ) AS pairs,
            	CAST( SUM( layEggsBreeders ) AS UNSIGNED ) AS layEggsBreeders,
            	CAST( SUM( results.layEggs )      / SUM( layEggsBreeders ) AS FLOAT ) AS layEggs,
            	CAST( SUM( layWeightBreeders ) AS UNSIGNED ) AS layWeightBreeders,
            	CAST( SUM( results.layWeight )    / SUM( layWeightBreeders ) AS FLOAT ) AS layWeight,
            	CAST( SUM( results.broodEggs ) AS UNSIGNED ) AS broodEggs,

            	CAST( SUM( broodHatchedBreeders ) AS UNSIGNED )AS broodHatchedBreeders,
            	CAST( SUM( results.broodHatched ) / SUM( broodHatchedBreeders ) AS FLOAT ) AS broodHatched,

				CAST( SUM( broodLayerFertileBreeders ) AS UNSIGNED ) AS broodLayerFertileBreeders,
            	CAST( SUM( results.broodLayerFertile ) / SUM( broodLayerFertileBreeders ) AS FLOAT ) AS broodLayerFertile,
            	CAST( SUM( broodLayerHatchedBreeders ) AS UNSIGNED )AS broodLayerHatchedBreeders,
            	CAST( SUM( results.broodLayerHatched ) / SUM( broodLayerHatchedBreeders ) AS FLOAT ) AS broodLayerHatched,

            	CAST( SUM( broodPigeonHatchedBreeders ) AS UNSIGNED )AS broodPigeonHatchedBreeders,
            	CAST( SUM( results.broodPigeonHatched ) / SUM( broodPigeonHatchedBreeders ) AS FLOAT ) AS broodPigeonHatched,
				CAST( SUM( broodPigeonResultBreeders ) AS UNSIGNED ) AS broodPigeonResultBreeders,
            	CAST( SUM( results.broodPigeonResult )  / SUM( broodPigeonResultBreeders ) AS FLOAT ) AS broodPigeonResult,

				CAST( SUM( results.showBreeders ) AS UNSIGNED ) AS showBreeders,
            	CAST( SUM( results.showCount ) AS UNSIGNED ) AS showCount,
            	CAST( SUM( results.showScore ) / SUM( showBreeders ) AS FLOAT ) AS showScore
            FROM
            	results
            GROUP BY districtId, `year`


        ');
		return Query::select($stmt, $args); // returns null, no results found, or single result
	}

	public static function forMap(int $year, ? int $sectionId, ? int $breedId, ? int $colorId, ? string $group ) : ? array {
		$startYear = START_YEAR;
		$args = get_defined_vars();
		$stmt = Query::prepare('
            SELECT
                count(*) AS count,
                CAST( :year      AS UNSIGNED ) AS `year`,
                CAST( district.rootId AS UNSIGNED ) AS districtId,
                CAST( :sectionId AS UNSIGNED )  AS sectionId, layers,
                CAST( :breedId AS UNSIGNED )    AS breedId,
                CAST( :colorId AS UNSIGNED )    AS colorId,
                :group                          AS `group`,

                CAST( district.rootId AS UNSIGNED ) AS id, # old way, supported for now
                district.name AS name, district.latitude, district.longitude, # for map
                
                # breeders for district and distinct pair breeders,  
                CAST( SUM( breeders ) AS UNSIGNED ) AS breeders, 
                
                # lay eggs
                CAST( SUM( IF( layEggs > 0, breeders, 0 ) ) AS UNSIGNED ) AS layBreeders,
                CAST( SUM( IF( layEggs > 0, breeders * layShould, 0 ) ) / SUM( IF( layEggs > 0, breeders, 0 ) ) AS DOUBLE ) AS layShould,
                CAST( SUM( IF( layEggs > 0, breeders * layEggs / layShould, 0 ) ) / SUM( IF( layEggs > 0, breeders, 0 ) ) AS DOUBLE ) AS layEggs,
                # lay weight
                CAST( SUM( IF( layWeight > 0, breeders, 0 ) ) AS UNSIGNED ) AS layWeightBreeders,
                CAST( SUM( IF( layWeight > 0, breeders * layWeightShould, 0 ) ) / SUM( IF( results.layWeight > 0, breeders, 0 ) ) AS DOUBLE ) AS layWeightShould,
                CAST( SUM( IF( layWeight > 0, breeders * layWeight / layWeightShould, 0 ) ) / SUM( IF( layWeight > 0, breeders, 0 ) ) AS DOUBLE ) AS layWeight,

                # brood all
                # CAST( SUM( IF( broodEggs > 0, breeders, 0 ) ) AS UNSIGNED ) AS broodBreeders,
                CAST( SUM( IF( broodHatched IS NOT NULL, breeders, 0 ) ) AS UNSIGNED ) AS broodBreeders,

                CAST( SUM( IF( broodEggs > 0, broodEggs, 0 ) ) AS UNSIGNED ) AS broodEggs,
                CAST( SUM( IF( broodEggs > 0 AND broodFertile IS NOT NULL, breeders * broodFertile / broodEggs, 0 ) ) / SUM( IF( broodEggs > 0 AND broodFertile IS NOT NULL, breeders, 0 ) ) AS DOUBLE ) AS broodFertile,
                CAST( SUM( IF( broodEggs > 0 AND broodHatched IS NOT NULL, breeders * broodHatched / broodEggs, 0 ) ) / SUM( IF( broodEggs > 0 AND broodHatched IS NOT NULL, breeders, 0 ) ) AS DOUBLE ) AS broodHatched,

                # brood layers
                CAST( SUM( IF( layers = 1 AND broodHatched IS NOT NULL, breeders, 0 ) ) AS UNSIGNED ) AS broodLayerBreeders,
                CAST( SUM( IF( layers = 1 AND broodEggs > 0, broodEggs, 0 ) ) AS UNSIGNED ) AS broodLayerEggs,
                CAST( SUM( IF( layers = 1 AND broodEggs > 0 AND broodFertile IS NOT NULL, breeders * broodFertile / broodEggs, 0 ) ) / SUM( IF( layers = 1 AND broodFertile IS NOT NULL, breeders, 0 ) ) AS DOUBLE ) AS broodLayerFertile,
                CAST( SUM( IF( layers = 1 AND broodEggs > 0 AND broodHatched IS NOT NULL, breeders * broodHatched / broodEggs, 0 ) ) / SUM( IF( layers = 1 AND broodHatched IS NOT NULL, breeders, 0 ) ) AS DOUBLE ) AS broodLayerHatched,
                # brood pigeons
                CAST( SUM( IF( layers = 0 AND broodHatched IS NOT NULL, breeders, 0 ) ) AS UNSIGNED ) AS broodPigeonBreeders,
                CAST( SUM( IF( layers = 0 AND broodEggs > 0, broodEggs, 0 ) ) AS UNSIGNED ) AS broodPigeonEggs,
                CAST( SUM( IF( layers = 0 AND broodEggs > 0 AND broodHatched IS NOT NULL, breeders * broodHatched / broodEggs, 0 ) ) / SUM( IF( layers = 0 AND broodEggs > 0 AND broodHatched IS NOT NULL, breeders, 0 ) ) AS DOUBLE ) AS broodPigeonHatched,
#                CAST( SUM( IF( layers = 0 AND broodEggs > 0 AND broodHatched IS NOT NULL, broodHatched, 0 ) ) AS UNSIGNED ) AS broodPigeonHatched,
                CAST( SUM( IF( layers = 0 AND broodHatched IS NOT NULL AND pairs > 0, breeders * broodHatched / pairs, 0 ) ) / SUM( IF( layers = 0 AND pairs > 0 AND broodHatched IS NOT NULL, breeders, 0 ) ) AS DOUBLE ) AS broodPigeonResult,
                # show
                CAST( SUM( IF( showCount > 0, breeders, 0 ) ) AS UNSIGNED ) AS showBreeders,
                CAST( SUM( showCount ) AS UNSIGNED ) AS showCount,
                CAST( SUM( IF( showScore IS NOT NULL, breeders * showScore, 0 ) ) / SUM( IF( showScore IS NOT NULL, breeders, 0 ) ) AS DOUBLE ) AS showScore

            FROM district
                LEFT JOIN (
                    # to group the breeders results for multiple pairs as one breeder, not per pair
                    SELECT
                        result.id, result.districtId, result.year, result.breeders,
                        section.id AS sectionId, section.name AS sectionName, section.order AS sectionOrder,
                        subsection.id AS subsectionId, subsection.name AS subsectionName, subsection.order AS subsectionOrder,

                        result.breedId, breed.name AS breedName,
                        result.colorId, IF( color.name IS NULL AND NOT section.id = 5, aocColor, color.name ) AS colorName, aocColor,
                        result.group,

                        breed.layEggs AS layShould, breed.layWeight AS layWeightShould,
                        SUM( pairs ) AS pairs, SUM( layDames ) AS layDames, AVG( result.layEggs ) AS layEggs, AVG( result.layWeight ) AS layWeight,
                        SUM( broodEggs ) AS broodEggs, SUM( broodFertile ) AS broodFertile, SUM( broodHatched ) AS broodHatched,
                        SUM( showCount ) AS showCount, AVG( showScore ) AS showScore,
                        section.layers

                    FROM result
                        LEFT JOIN pair ON pair.id = result.pairId
                        LEFT JOIN breed ON breed.id = result.breedId
                        LEFT JOIN color ON color.id = result.colorId
                        LEFT JOIN section AS subsection ON subsection.id = breed.sectionId
                        LEFT JOIN section ON section.id = subsection.parentId
                    WHERE
                        result.year = :year

                       	AND ( pair.id IS NULL OR pair.accepted = 1 )

                        AND ( :sectionId IS NULL OR breed.sectionId IN (
                            SELECT DISTINCT child.id FROM section AS parent                                  # root could be 2, geflügel
                                LEFT JOIN section AS child ON child.parentId=parent.id OR child.id=parent.id # and children and repeat parent
                            WHERE parent.id=:sectionId OR parent.parentId=:sectionId
                        ))
                        AND ( :breedId IS NULL OR result.breedId = :breedId )
                        AND ( :colorId IS NULL OR result.colorId = :colorId )
                        AND ( :group   IS NULL OR result.group   = :group )
                    GROUP BY result.year, result.districtId, result.breedId, result.colorId, result.group, pair.breederId
                ) AS results ON results.districtId = district.id

            GROUP BY district.rootId # all LV
        ');
		return Query::selectArray($stmt, $args); // returns null, no results found, or single result
	}


	// report for all districts in a year (map)
	public static function forMapOld(int $year, ? int $sectionId, ? int $breedId, ? int $colorId, ? string $group ) : ? array {
		$startYear = START_YEAR;
		$args = get_defined_vars();
		$stmt = Query::prepare('
            SELECT
                count(*) AS count,
                CAST( :year      AS UNSIGNED ) AS `year`,
                CAST( district.rootId AS UNSIGNED ) AS districtId,
                CAST( :sectionId AS UNSIGNED )  AS sectionId, layers,
                CAST( :breedId AS UNSIGNED )    AS breedId,
                CAST( :colorId AS UNSIGNED )    AS colorId,
                :group                          AS `group`,

                CAST( district.rootId AS UNSIGNED ) AS id, # old way, supported for now
                district.name AS name, district.latitude, district.longitude, # for map

                # breeders for district and distinct pair breeders,
                CAST( SUM( breeders ) AS UNSIGNED ) AS breeders,

                # lay eggs
                CAST( SUM( IF( layEggs > 0, breeders, 0 ) ) AS UNSIGNED ) AS layBreeders,
                CAST( SUM( IF( layEggs > 0, breeders * layShould, 0 ) ) / SUM( IF( layEggs > 0, breeders, 0 ) ) AS DOUBLE ) AS layShould,
                CAST( SUM( IF( layEggs > 0, breeders * layEggs / layShould, 0 ) ) / SUM( IF( layEggs > 0, breeders, 0 ) ) AS DOUBLE ) AS layEggs,
                # lay weight
                CAST( SUM( IF( layWeight > 0, breeders, 0 ) ) AS UNSIGNED ) AS layWeightBreeders,
                CAST( SUM( IF( layWeight > 0, breeders * layWeightShould, 0 ) ) / SUM( IF( results.layWeight > 0, breeders, 0 ) ) AS DOUBLE ) AS layWeightShould,
                CAST( SUM( IF( layWeight > 0, breeders * layWeight / layWeightShould, 0 ) ) / SUM( IF( layWeight > 0, breeders, 0 ) ) AS DOUBLE ) AS layWeight,

                # brood all
                # CAST( SUM( IF( broodEggs > 0, breeders, 0 ) ) AS UNSIGNED ) AS broodBreeders,
                CAST( SUM( IF( broodHatched IS NOT NULL, breeders, 0 ) ) AS UNSIGNED ) AS broodBreeders,

                CAST( SUM( IF( broodEggs > 0, broodEggs, 0 ) ) AS UNSIGNED ) AS broodEggs,
                CAST( SUM( IF( broodEggs > 0 AND broodFertile IS NOT NULL, breeders * broodFertile / broodEggs, 0 ) ) / SUM( IF( broodEggs > 0 AND broodFertile IS NOT NULL, breeders, 0 ) ) AS DOUBLE ) AS broodFertile,
                CAST( SUM( IF( broodEggs > 0 AND broodHatched IS NOT NULL, breeders * broodHatched / broodEggs, 0 ) ) / SUM( IF( broodEggs > 0 AND broodHatched IS NOT NULL, breeders, 0 ) ) AS DOUBLE ) AS broodHatched,

                # brood layers
                CAST( SUM( IF( layers = 1 AND broodHatched IS NOT NULL, breeders, 0 ) ) AS UNSIGNED ) AS broodLayerBreeders,
                CAST( SUM( IF( layers = 1 AND broodEggs > 0, broodEggs, 0 ) ) AS UNSIGNED ) AS broodLayerEggs,
                CAST( SUM( IF( layers = 1 AND broodEggs > 0 AND broodFertile IS NOT NULL, breeders * broodFertile / broodEggs, 0 ) ) / SUM( IF( layers = 1 AND broodFertile IS NOT NULL, breeders, 0 ) ) AS DOUBLE ) AS broodLayerFertile,
                CAST( SUM( IF( layers = 1 AND broodEggs > 0 AND broodHatched IS NOT NULL, breeders * broodHatched / broodEggs, 0 ) ) / SUM( IF( layers = 1 AND broodHatched IS NOT NULL, breeders, 0 ) ) AS DOUBLE ) AS broodLayerHatched,
                # brood pigeons
                CAST( SUM( IF( layers = 0 AND broodHatched IS NOT NULL, breeders, 0 ) ) AS UNSIGNED ) AS broodPigeonBreeders,
                CAST( SUM( IF( layers = 0 AND broodEggs > 0, broodEggs, 0 ) ) AS UNSIGNED ) AS broodPigeonEggs,
                CAST( SUM( IF( layers = 0 AND broodEggs > 0 AND broodHatched IS NOT NULL, breeders * broodHatched / broodEggs, 0 ) ) / SUM( IF( layers = 0 AND broodEggs > 0 AND broodHatched IS NOT NULL, breeders, 0 ) ) AS DOUBLE ) AS broodPigeonHatched,
#                CAST( SUM( IF( layers = 0 AND broodEggs > 0 AND broodHatched IS NOT NULL, broodHatched, 0 ) ) AS UNSIGNED ) AS broodPigeonHatched,
                CAST( SUM( IF( layers = 0 AND broodHatched IS NOT NULL AND pairs > 0, breeders * broodHatched / pairs, 0 ) ) / SUM( IF( layers = 0 AND pairs > 0 AND broodHatched IS NOT NULL, breeders, 0 ) ) AS DOUBLE ) AS broodPigeonResult,
                # show
                CAST( SUM( IF( showCount > 0, breeders, 0 ) ) AS UNSIGNED ) AS showBreeders,
                CAST( SUM( showCount ) AS UNSIGNED ) AS showCount,
                CAST( SUM( IF( showScore IS NOT NULL, breeders * showScore, 0 ) ) / SUM( IF( showScore IS NOT NULL, breeders, 0 ) ) AS DOUBLE ) AS showScore

            FROM district
                LEFT JOIN (
                    # to group the breeders results for multiple pairs as one breeder, not per pair
                    SELECT
                        result.id, result.districtId, result.year, result.breeders,
                        section.id AS sectionId, section.name AS sectionName, section.order AS sectionOrder,
                        subsection.id AS subsectionId, subsection.name AS subsectionName, subsection.order AS subsectionOrder,

                        result.breedId, breed.name AS breedName,
                        result.colorId, IF( color.name IS NULL AND NOT section.id = 5, aocColor, color.name ) AS colorName, aocColor,
                        result.group,

                        breed.layEggs AS layShould, breed.layWeight AS layWeightShould,
                        SUM( pairs ) AS pairs, SUM( layDames ) AS layDames, AVG( result.layEggs ) AS layEggs, AVG( result.layWeight ) AS layWeight,
                        SUM( broodEggs ) AS broodEggs, SUM( broodFertile ) AS broodFertile, SUM( broodHatched ) AS broodHatched,
                        SUM( showCount ) AS showCount, AVG( showScore ) AS showScore,
                        section.layers

                    FROM result
                        LEFT JOIN pair ON pair.id = result.pairId
                        LEFT JOIN breed ON breed.id = result.breedId
                        LEFT JOIN color ON color.id = result.colorId
                        LEFT JOIN section AS subsection ON subsection.id = breed.sectionId
                        LEFT JOIN section ON section.id = subsection.parentId
                    WHERE
                        result.year = :year

                       	AND ( pair.id IS NULL OR pair.accepted = 1 )

                        AND ( :sectionId IS NULL OR breed.sectionId IN (
                            SELECT DISTINCT child.id FROM section AS parent                                  # root could be 2, geflügel
                                LEFT JOIN section AS child ON child.parentId=parent.id OR child.id=parent.id # and children and repeat parent
                            WHERE parent.id=:sectionId OR parent.parentId=:sectionId
                        ))
                        AND ( :breedId IS NULL OR result.breedId = :breedId )
                        AND ( :colorId IS NULL OR result.colorId = :colorId )
                        AND ( :group   IS NULL OR result.group   = :group )
                    GROUP BY result.year, result.districtId, result.breedId, result.colorId, result.group, pair.breederId
                ) AS results ON results.districtId = district.id

            GROUP BY district.rootId # all LV
        ');
		return Query::selectArray($stmt, $args); // returns null, no results found, or single result
	}


	// report for district , all years (trend)
//  public static function forTrend(int $districtId, ? int $sectionId, ? int $breedId, ? int $colorId, ? string $group ) : ? array {
//
//		$startYear = START_YEAR;
//		$args = get_defined_vars();
//		$stmt = Query::prepare('
//            WITH RECURSIVE years AS (
//                SELECT :startYear AS year
//                UNION ALL
//                SELECT year + 1 FROM years WHERE year + 1 <= YEAR(NOW())
//            ),
//
//            districts AS (
//                SELECT child.id
//                FROM district AS parent
//                LEFT JOIN district AS child
//                  ON child.parentId = parent.id OR child.id = parent.id
//                WHERE parent.id = :districtId OR parent.parentId = :districtId
//            ),
//
//            sections AS (
//                SELECT child.id
//                FROM section AS parent
//                LEFT JOIN section AS child
//                  ON child.parentId = parent.id OR child.id = parent.id
//                WHERE (:sectionId IS NULL)
//                   OR parent.id = :sectionId
//                   OR parent.parentId = :sectionId
//            ),
//
//            results AS (
//                SELECT
//                    r.id,
//                    r.year,
//                    r.districtId,
//                    r.breeders,
//                    r.breedId,
//                    r.colorId,
//                    r.group,
//                    b.layEggs AS layShould,
//                    b.layWeight AS layWeightShould,
//
//                    SUM(r.pairs) AS pairs,
//                    SUM(r.layDames) AS layDames,
//                    AVG(r.layEggs) AS layEggs,
//                    AVG(r.layWeight) AS layWeight,
//
//                    SUM(r.broodEggs) AS broodEggs,
//                    SUM(r.broodFertile) AS broodFertile,
//                    SUM(r.broodHatched) AS broodHatched,
//
//                    SUM(r.showCount) AS showCount,
//                    AVG(r.showScore) AS showScore,
//
//                    s.layers
//
//                FROM result r
//                LEFT JOIN pair p ON p.id = r.pairId AND p.accepted = 1
//                LEFT JOIN breed b ON b.id = r.breedId
//                LEFT JOIN color c ON c.id = r.colorId
//                LEFT JOIN section ss ON ss.id = b.sectionId
//                LEFT JOIN section s ON s.id = ss.parentId
//
//                WHERE r.districtId IN (SELECT id FROM districts)
//                  AND (:sectionId IS NULL OR b.sectionId IN (SELECT id FROM sections))
//                  AND (:breedId IS NULL OR r.breedId = :breedId)
//                  AND (:colorId IS NULL OR r.colorId = :colorId)
//                  AND (:group IS NULL OR r.group = :group)
//
//                GROUP BY r.year, r.districtId, r.breedId, r.colorId, r.group, p.breederId
//            )
//
//            SELECT
//                years.year AS year,
//
//                COUNT(r.id) AS count,
//
//                :districtId AS districtId,
//                :sectionId AS sectionId,
//                :breedId AS breedId,
//                :colorId AS colorId,
//                :group AS `group`,
//
//                COALESCE(r.layers, 0) AS layers,
//
//                SUM(r.breeders) AS breeders,
//
//                -- lay eggs
//                SUM(CASE WHEN r.layEggs > 0 THEN r.breeders END) AS layBreeders,
//                SUM(CASE WHEN r.layEggs > 0 THEN r.breeders * r.layShould END)
//                    / NULLIF(SUM(CASE WHEN r.layEggs > 0 THEN r.breeders END),0) AS layShould,
//                SUM(CASE WHEN r.layEggs > 0 THEN r.breeders * r.layEggs / r.layShould END)
//                    / NULLIF(SUM(CASE WHEN r.layEggs > 0 THEN r.breeders END),0) AS layEggs,
//
//                -- lay weight
//                SUM(CASE WHEN r.layWeight > 0 THEN r.breeders END) AS layWeightBreeders,
//                SUM(CASE WHEN r.layWeight > 0 THEN r.breeders * r.layWeightShould END)
//                    / NULLIF(SUM(CASE WHEN r.layWeight > 0 THEN r.breeders END),0) AS layWeightShould,
//                SUM(CASE WHEN r.layWeight > 0 THEN r.breeders * r.layWeight / r.layWeightShould END)
//                    / NULLIF(SUM(CASE WHEN r.layWeight > 0 THEN r.breeders END),0) AS layWeight,
//
//                -- brood
//                SUM(CASE WHEN r.broodEggs > 0 THEN r.broodEggs END) AS broodEggs,
//                SUM(CASE WHEN r.broodEggs > 0 AND r.broodFertile IS NOT NULL
//                         THEN r.breeders * r.broodFertile / r.broodEggs END)
//                    / NULLIF(SUM(CASE WHEN r.broodEggs > 0 AND r.broodFertile IS NOT NULL THEN r.breeders END),0)
//                    AS broodFertile,
//
//                SUM(CASE WHEN r.broodEggs > 0 AND r.broodHatched IS NOT NULL
//                         THEN r.breeders * r.broodHatched / r.broodEggs END)
//                    / NULLIF(SUM(CASE WHEN r.broodEggs > 0 AND r.broodHatched IS NOT NULL THEN r.breeders END),0)
//                    AS broodHatched,
//
//                SUM(CASE WHEN r.broodHatched IS NOT NULL THEN r.breeders END) AS broodBreeders,
//
//                -- brood layers
//                SUM(CASE WHEN r.layers = 1 AND r.broodHatched IS NOT NULL THEN r.breeders END) AS broodLayerBreeders,
//                SUM(CASE WHEN r.layers = 1 AND r.broodEggs > 0 THEN r.broodEggs END) AS broodLayerEggs,
//
//                SUM(CASE WHEN r.layers = 1 AND r.broodEggs > 0 AND r.broodFertile IS NOT NULL
//                         THEN r.breeders * r.broodFertile / r.broodEggs END)
//                    / NULLIF(SUM(CASE WHEN r.layers = 1 AND r.broodEggs > 0 AND r.broodFertile IS NOT NULL THEN r.breeders END),0)
//                    AS broodLayerFertile,
//
//                SUM(CASE WHEN r.layers = 1 AND r.broodEggs > 0 AND r.broodHatched IS NOT NULL
//                         THEN r.breeders * r.broodHatched / r.broodEggs END)
//                    / NULLIF(SUM(CASE WHEN r.layers = 1 AND r.broodEggs > 0 AND r.broodHatched IS NOT NULL THEN r.breeders END),0)
//                    AS broodLayerHatched,
//
//                -- brood pigeons
//                SUM(CASE WHEN r.layers = 0 AND r.broodHatched IS NOT NULL THEN r
//        ');
//		return Query::selectArray($stmt, $args); // returns null, no results found, or single result
//	}

  public static function forTrend(int $districtId, ? int $sectionId, ? int $breedId, ? int $colorId, ? string $group ) : ? array {

		$startYear = START_YEAR;
		$args = get_defined_vars();
		$stmt = Query::prepare('
            SELECT
                count(*) AS count,
                CAST( years.year  AS UNSIGNED ) AS `year`,
                CAST( :districtId AS UNSIGNED ) AS districtId,
                CAST( :sectionId AS UNSIGNED )  AS sectionId, layers,
                CAST( :breedId AS UNSIGNED )    AS breedId,
                CAST( :colorId AS UNSIGNED )    AS colorId,
                :group                          AS `group`,

                # breeders for district and distinct pair breeders,
                CAST( SUM( breeders ) AS UNSIGNED ) AS breeders,
                # lay eggs
                CAST( SUM( layEggBreeders ) AS UNSIGNED ) AS layBreeders,
                CAST( SUM( layEggBreeders * layShould ) / SUM( layEggBreeders ) AS FLOAT ) AS layShould, 
                CAST( SUM( layEggs ) / NULLIF( SUM( layEggBreeders ), 0 ) AS FLOAT ) AS layEggs,

                # lay weight
                CAST( SUM( results.layWeightBreeders ) AS UNSIGNED ) AS layWeightBreeders,
                CAST( SUM( layWeightBreeders * layWeightShould ) / SUM( layWeightBreeders ) AS DOUBLE ) AS layWeightShould,
                CAST( SUM( layWeight ) / NULLIF( SUM( layWeightBreeders ), 0 ) AS FLOAT ) AS layWeight,

                # brood
#                CAST( SUM( broodBreeders) AS UNSIGNED ) AS broodBreeders, # layers and pigeons
                # brood layers
#                CAST( SUM( broodLayerBreeders) AS UNSIGNED ) AS broodLayerBreeders,
#                CAST( SUM( broodLayerEggs) AS UNSIGNED ) AS broodLayerEggs,
#                CAST( SUM( broodLayerFertile) / NULLIF( SUM( broodLayerBreeders ),0 ) AS FLOAT ) AS broodLayerFertile,
#                CAST( SUM( broodLayerHatched) / NULLIF( SUM( broodLayerBreeders ),0 ) AS FLOAT ) AS broodLayerHatched,

                #brood
                CAST( SUM( broodBreeders) AS UNSIGNED ) AS broodBreeders,
                #layer
                CAST( SUM( broodLayerBreeders) AS UNSIGNED ) AS broodLayerBreeders,
                CAST( SUM( broodLayerEggs) AS UNSIGNED ) AS broodLayerEggs,

                CAST( SUM( broodLayerFertileBreeders) AS UNSIGNED ) AS broodLayerFertileBreeders,
                CAST( SUM( broodLayerFertile) / NULLIF( SUM( broodLayerFertileBreeders ),0 ) AS FLOAT ) AS broodLayerFertile,

                CAST( SUM( broodLayerHatchedBreeders) AS UNSIGNED ) AS broodLayerHatchedBreeders,
                CAST( SUM( broodLayerHatched) / NULLIF( SUM( broodLayerHatchedBreeders ),0 ) AS FLOAT ) AS broodLayerHatched,



                # pigeons result
                CAST( SUM( broodPigeonBreeders ) AS UNSIGNED ) AS broodPigeonBreeders,
                CAST( SUM( broodPigeonResult) / NULLIF( SUM( results.broodPigeonBreeders ),0 ) AS FLOAT ) AS broodPigeonResult,
                # pigeon broods
                CAST( SUM( broodPigeonEggsBreeders) AS UNSIGNED ) AS broodPigeonEggsBreeders,
                CAST( SUM( broodPigeonEggs ) AS UNSIGNED ) AS broodPigeonEggs,
                CAST( SUM( broodPigeonHatched ) / NULLIF( SUM( broodPigeonBreeders ),0 ) AS FLOAT ) AS broodPigeonHatched,

                # show
                CAST( SUM( showBreeders) AS UNSIGNED ) AS showBreeders,
                CAST( SUM( showCount) AS UNSIGNED ) AS showCount,
                CAST( SUM( showScore ) / NULLIF( SUM( showBreeders ),0 ) AS FLOAT ) AS showScore

            FROM ( # generate years
                SELECT @year:=YEAR( NOW() )+1
            ) AS init, (
                SELECT @year:=@year-1 AS `year`
                FROM color
                WHERE @year > :startYear
            ) AS years

                LEFT JOIN (
                    # to group the breeder his results for multiple pairs as one breeder, not per pair
                    SELECT
                        result.id, result.districtId, result.year, result.group,

                        SUM( IF( result.pairId IS NULL, result.breeders, 0 ) ) + COUNT( DISTINCT pair.breederId ) AS breeders,

                        section.id AS sectionId, section.name AS sectionName, section.order AS sectionOrder,
                        subsection.id AS subsectionId, subsection.name AS subsectionName, subsection.order AS subsectionOrder,

                        result.breedId, breed.name AS breedName,

                        result.colorId, ## differs from table, now using pigeon color
                        IF( color.name IS NULL AND section.id <> 5, aocColor, color.name ) AS colorName, aocColor,

                        breed.layEggs AS layShould,
                        breed.layWeight AS layWeightShould,

                        SUM( pairs ) AS pairs,
                        SUM( layDames ) AS layDames,

                        # lay eggs
                        SUM( IF( result.layEggs > 0, result.breeders, 0 ))  AS layEggBreeders,
                        SUM( result.breeders * result.layEggs ) / breed.layEggs AS layEggs,
                        # lay weight
                        SUM( IF( result.layWeight > 0, result.breeders, 0 ) ) AS layWeightBreeders,
                        SUM( result.breeders * result.layWeight ) / breed.layWeight AS layWeight,

                        # brood
                        SUM( IF( broodHatched IS NOT NULL, result.breeders, 0 ) ) AS broodBreeders,

                        # layers
#                        SUM( IF( subsection.layers = 1 AND broodHatched IS NOT NULL, breeders, 0 )) AS broodLayerBreeders,
#                        SUM( IF( subsection.layers = 1 AND broodEggs IS NOT NULL, broodEggs, 0 )) AS broodLayerEggs,
#                        SUM( IF( subsection.layers = 1 AND broodEggs > 0 AND broodFertile IS NOT NULL, result.breeders * broodFertile / broodEggs, 0 ) ) AS broodLayerFertile,
#                        SUM( IF( subsection.layers = 1 AND broodEggs > 0 AND broodHatched IS NOT NULL, result.breeders * broodHatched / broodEggs, 0 ) ) AS broodLayerHatched,


                        #layers
                        SUM( IF( subsection.layers = 1 AND broodHatched IS NOT NULL, breeders, 0 ) ) AS broodLayerBreeders,
                        SUM( IF( subsection.layers = 1 AND broodEggs IS NOT NULL, broodEggs, 0 )) AS broodLayerEggs,
                        SUM( IF( subsection.layers = 1 AND broodEggs > 0 AND broodFertile IS NOT NULL, breeders, 0 )) AS broodLayerFertileBreeders,
                        SUM( IF( subsection.layers = 1 AND broodEggs > 0 AND broodFertile IS NOT NULL, result.breeders * broodFertile / broodEggs, 0 ) ) AS broodLayerFertile,
                        SUM( IF( subsection.layers = 1 AND broodEggs > 0 AND broodHatched IS NOT NULL, breeders, 0 )) AS broodLayerHatchedBreeders,
                        SUM( IF( subsection.layers = 1 AND broodEggs > 0 AND broodHatched IS NOT NULL, result.breeders * broodHatched / broodEggs, 0 ) ) AS broodLayerHatched,


                        # pigeons
                        #SUM( IF( subsection.layers = 0 AND broodHatched IS NOT NULL, breeders, 0 )) AS broodPigeonBreeders,
                        #SUM( IF( subsection.layers = 0 AND broodEggs > 0 AND broodHatched IS NOT NULL, breeders, 0 )) AS broodPigeonEggsBreeders,

                        # pigeon result, need pair and hatched
                        SUM( IF( subsection.layers = 0 AND pairs > 0 AND broodHatched IS NOT NULL, breeders, 0 )) AS broodPigeonBreeders, #result
                        SUM( IF( subsection.layers = 0 AND pairs > 0 AND broodHatched IS NOT NULL, result.breeders * broodHatched / pairs, 0 )) AS broodPigeonResult,
                        # pigeon brood, need broods and hatched
                        SUM( IF( subsection.layers = 0 AND broodEggs > 0 AND broodHatched IS NOT NULL, breeders, 0 )) AS broodPigeonEggsBreeders, #hatch
                        SUM( IF( subsection.layers = 0 AND broodEggs > 0, broodEggs, 0 )) AS broodPigeonEggs,
                        SUM( IF( subsection.layers = 0 AND broodHatched IS NOT NULL, result.breeders * broodHatched / broodEggs, 0 )) AS broodPigeonHatched,

                        #show
                        #SUM( showCount ) AS showCount,
                        #AVG( showScore ) AS showScore,

                        SUM( IF( showCount > 0, result.breeders, 0 ) ) AS showBreeders,
                        SUM( showCount ) AS showCount,
                        SUM( IF( showScore IS NOT NULL, result.breeders * showScore, 0 ) ) AS showScore,

                        subsection.layers

                    FROM result
                        LEFT JOIN pair ON pair.id = result.pairId
                        LEFT JOIN breed ON breed.id = result.breedId
                        LEFT JOIN color ON color.id = result.colorId
                        LEFT JOIN section AS subsection ON subsection.id = breed.sectionId
                        LEFT JOIN section ON section.id = subsection.parentId
                    WHERE
                        result.districtId IN (
                            SELECT DISTINCT child.id FROM district AS parent
                                LEFT JOIN district AS child ON child.parentId = parent.id OR child.id = parent.id
                            WHERE parent.id=:districtId OR parent.parentId=:districtId
                        )
                        AND ( :sectionId IS NULL OR breed.sectionId IN (
                            SELECT DISTINCT child.id FROM section AS parent                                  # root could be 2, geflügel
                                LEFT JOIN section AS child ON child.parentId=parent.id OR child.id=parent.id # and children and repeat parent
                            WHERE parent.id=:sectionId OR parent.parentId=:sectionId
                        ))
                        AND ( :breedId IS NULL OR result.breedId = :breedId )
                        AND ( :colorId IS NULL OR result.colorId = :colorId )
                        AND ( :group   IS NULL OR result.group   = :group )

                       	AND ( pair.id IS NULL OR pair.accepted = 1 )

                    GROUP BY result.year, result.districtId, result.breedId, result.colorId, result.group, pair.breederId
                ) AS results ON results.year = years.year

            GROUP BY years.year
            ORDER BY years.year
        ');
		return Query::selectArray($stmt, $args); // returns null, no results found, or single result
	}
	// for use in district year report table?

	public static function forTable(int $districtId, int $year, ? string $group ) : ? array {
		$args = get_defined_vars();
		$stmt = Query::prepare("
            WITH
            	RECURSIVE districts AS (
            		SELECT id, parentId
            		FROM district
            		WHERE id = :districtId

            		UNION ALL

            		SELECT d.id, d.parentId
            		FROM district d
            		JOIN districts p ON d.parentId = p.id
            	),

            	results AS (
            		SELECT
            			result.sectionId, result.breedId,
            			CASE WHEN result.sectionId = 5 THEN NULL ELSE result.colorId END AS colorId,
            			aocColor,
            			SUM( CASE WHEN pairId IS NULL THEN breeders ELSE 0 END ) + COUNT( DISTINCT pair.breederId ) AS breeders,
                        SUM( pairs ) AS pairs,
            			SUM( CASE WHEN result.layEggs > 0 THEN breeders ELSE 0 END ) AS layEggsBreeders,
            			SUM( breeders * result.layEggs ) AS layEggs,
            			SUM( CASE WHEN result.layWeight > 0 THEN breeders ELSE 0 END ) AS layWeightBreeders,
            			SUM( breeders * result.layWeight ) AS layWeight,

            			SUM( broodEggs ) AS broodEggs,
            			SUM( CASE WHEN broodEggs > 0 AND broodFertile >= 0 THEN breeders ELSE 0 END ) AS broodFertileBreeders,
            			SUM( CASE WHEN broodEggs > 0 AND broodFertile >= 0 THEN breeders * broodFertile / broodEggs ELSE NULL END ) AS broodFertile,
            			SUM( CASE WHEN broodEggs > 0 AND broodHatched >= 0 THEN breeders ELSE 0 END ) AS broodHatchedBreeders,
            			SUM( CASE WHEN broodEggs > 0 AND broodHatched >= 0 THEN breeders * broodHatched / broodEggs ELSE NULL END ) AS broodHatched,
            			SUM( CASE WHEN pairs > 0 AND broodHatched >= 0 THEN breeders ELSE 0 END ) AS broodResultBreeders,
            			SUM( CASE WHEN pairs > 0 AND broodHatched >= 0 THEN breeders * broodHatched / pairs ELSE 0 END ) AS broodResult,

            			SUM( CASE WHEN showCount > 0 THEN breeders ELSE 0 END ) AS showBreeders,
            			SUM( showCount ) AS showCount,
            			SUM( breeders * showScore ) AS showScore

            		FROM
            			result
            			LEFT JOIN pair ON pair.id = result.pairId
            		WHERE
            			result.districtId IN ( SELECT id FROM districts )
            			AND result.year = :year
            			AND  ( :group IS NULL OR result.group = :group ) # group  resplaced by NULL

            		GROUP BY result.breedId, result.colorId, result.aocColor
            	)

            SELECT
            	section.id AS sectionId, section.name AS sectionName,
            	subsection.id AS subsectionId, subsection.name AS subsectionName, subsection.order AS sectionOrder,
            	results.breedId, breed.name AS breedName,
            	results.colorId,
            	CASE WHEN section.layers AND results.colorId IS NULL THEN aocColor ELSE color.name END AS colorName,
            	results.aocColor AS aocColor,

            	breed.layEggs AS layEggsShould,
                breed.layWeight AS layWeightShould,

            	CAST( SUM( breeders ) AS UNSIGNED ) AS breeders,
                CAST( SUM( pairs ) AS UNSIGNED ) AS pairs,
            	CAST( SUM( layEggsBreeders ) AS UNSIGNED ) AS layEggsBreeders,
            	CAST( SUM( results.layEggs )      / SUM( layEggsBreeders ) AS FLOAT ) AS layEggs,
            	CAST( SUM( layWeightBreeders ) AS UNSIGNED ) AS layWeightBreeders,
            	CAST( SUM( results.layWeight )    / SUM( layWeightBreeders ) AS FLOAT ) AS layWeight,
            	CAST( SUM( results.broodEggs ) AS UNSIGNED ) AS broodEggs,
            	CAST( SUM( broodFertileBreeders ) AS UNSIGNED ) AS broodFertileBreeders,
            	CAST( SUM( results.broodFertile ) / SUM( broodFertileBreeders ) AS FLOAT ) AS broodFertile,
            	CAST( SUM( broodHatchedBreeders ) AS UNSIGNED )AS broodHatchedBreeders,
            	CAST( SUM( results.broodHatched ) / SUM( broodHatchedBreeders ) AS FLOAT ) AS broodHatched,
            	CAST( SUM( broodResultBreeders ) AS UNSIGNED ) AS broodResultBreeders,
            	CAST( SUM( results.broodResult )  / SUM( broodResultBreeders ) AS FLOAT ) AS broodResult,
            	CAST( SUM( results.showBreeders ) AS UNSIGNED ) AS showBreeders,
            	CAST( SUM( results.showCount ) AS UNSIGNED ) AS showCount,
            	CAST( SUM( results.showScore ) / SUM( showBreeders ) AS FLOAT ) AS showScore,
            	section.layers
            FROM
            	results
            	LEFT JOIN color ON color.id = results.colorId
            	LEFT JOIN breed ON breed.id = results.breedId
            	LEFT JOIN section AS subsection ON subsection.id = breed.sectionId
            	LEFT JOIN section ON section.id = subsection.parentId
            GROUP BY results.sectionId, results.breedId, results.colorId, results.aocColor
            ORDER BY sectionOrder, breedName, colorName
        ");
		return Query::selectArray( $stmt, $args );
	}



//	public static function forTable(int $districtId, int $year, ? string $group ) : ? array {
//		$args = get_defined_vars();
//		$stmt = Query::prepare("
//            SELECT
//                :districtId AS districtId, :year AS `year`, :group AS `group`,
//                sectionId, sectionName, sectionOrder, layers,
//                subsectionId, subsectionName, subsectionOrder,
//                breedId, breedName,
//                layShould, layWeightShould,
//                colorId, colorName, aocColor,
//
//                CAST( SUM( breeders ) AS UNSIGNED ) AS breeders,
//                CAST( SUM( pairs ) AS UNSIGNED ) AS pairs,
//                CAST( SUM( layDames ) AS UNSIGNED ) AS layDames,
//
//                # lay eggs
//                CAST( SUM( layEggBreeders ) AS UNSIGNED ) AS layBreeders,
//                CAST( SUM( layEggs ) / NULLIF( SUM( layEggBreeders ), 0 )  AS FLOAT ) AS layEggs,
//                # lay weight
//                CAST( SUM( results.layWeightBreeders ) AS UNSIGNED ) AS layWeightBreeders,
//                CAST( SUM( layWeight ) / NULLIF( SUM( layWeightBreeders ), 0 ) AS FLOAT ) AS layWeight,
//
//                #brood
//                CAST( SUM( broodBreeders) AS UNSIGNED ) AS broodBreeders,
//                #layer
//                CAST( SUM( broodLayerBreeders) AS UNSIGNED ) AS broodLayerBreeders,
//                CAST( SUM( broodLayerEggs) AS UNSIGNED ) AS broodLayerEggs,
//
//                CAST( SUM( broodLayerFertileBreeders) AS UNSIGNED ) AS broodLayerFertileBreeders,
//                CAST( SUM( broodLayerFertile) / NULLIF( SUM( broodLayerFertileBreeders ),0 ) AS FLOAT ) AS broodLayerFertile,
//
//                CAST( SUM( broodLayerHatchedBreeders) AS UNSIGNED ) AS broodLayerHatchedBreeders,
//                CAST( SUM( broodLayerHatched) / NULLIF( SUM( broodLayerHatchedBreeders ),0 ) AS FLOAT ) AS broodLayerHatched,
//
//                # pigeon result
//                CAST( SUM( broodPigeonResultBreeders ) AS UNSIGNED ) AS broodPigeonResultBreeders,
//                CAST( SUM( broodPigeonResult) / NULLIF( SUM( results.broodPigeonResultBreeders ),0 ) AS FLOAT ) AS broodPigeonResult,
//                # pigeon broods
//                CAST( SUM( broodPigeonEggsBreeders) AS UNSIGNED ) AS broodPigeonEggsBreeders,
//                CAST( SUM( broodPigeonEggs ) AS UNSIGNED ) AS broodPigeonEggs,
//                CAST( SUM( broodPigeonHatched ) / NULLIF( SUM( broodPigeonEggsBreeders ),0 ) AS FLOAT ) AS broodPigeonHatched,
//
//                CAST( SUM( showBreeders) AS UNSIGNED ) AS showBreeders,
//                CAST( SUM( showCount) AS UNSIGNED ) AS showCount,
//                CAST( SUM( showScore ) / NULLIF( SUM( showBreeders ),0 ) AS FLOAT ) AS showScore
//
//            FROM (
//                SELECT
//                    result.districtId, result.year, result.group,
//
//
//                    section.id AS sectionId, section.name AS sectionName, section.order AS sectionOrder,
//                    subsection.id AS subsectionId, subsection.name AS subsectionName, subsection.order AS subsectionOrder,
//
//                    result.breedId, breed.name AS breedName,
//
//                    IF( section.id = 5, NULL, result.colorId) AS colorId,
//                    IF( result.colorid IS NULL AND section.id <> 5, aocColor, color.name) AS colorName, aocColor,
//
//                    breed.layEggs AS layShould,
//                    breed.layWeight AS layWeightShould,
//
//                    SUM( IF( result.pairId IS NULL, result.breeders, 0 ) ) + COUNT( DISTINCT pair.breederId ) AS breeders,
//                    SUM( pairs ) AS pairs,
//                    SUM( layDames ) AS layDames,
//
//                    # Lay eggs
//                    SUM( IF( result.layEggs > 0, result.breeders, 0 ))  AS layEggBreeders,
//                    SUM( result.breeders * result.layEggs ) / breed.layEggs AS layEggs,
//                    # lay weight
//                    SUM( IF( result.layWeight > 0, result.breeders, 0 ) ) AS layWeightBreeders,
//                    SUM( result.breeders * result.layWeight ) / breed.layWeight AS layWeight,
//
//                    #brood
//                    SUM( IF( broodHatched IS NOT NULL, result.breeders, 0 ) ) AS broodBreeders,
//
//                    #layers
//                    SUM( IF( subsection.layers = 1 AND broodHatched IS NOT NULL, breeders, 0 ) ) AS broodLayerBreeders,
//                    SUM( IF( subsection.layers = 1 AND broodEggs IS NOT NULL, broodEggs, 0 )) AS broodLayerEggs,
//                    SUM( IF( subsection.layers = 1 AND broodEggs > 0 AND broodFertile IS NOT NULL, breeders, 0 )) AS broodLayerFertileBreeders,
//                    SUM( IF( subsection.layers = 1 AND broodEggs > 0 AND broodFertile IS NOT NULL, result.breeders * broodFertile / broodEggs, 0 ) ) AS broodLayerFertile,
//                    SUM( IF( subsection.layers = 1 AND broodEggs > 0 AND broodHatched IS NOT NULL, breeders, 0 )) AS broodLayerHatchedBreeders,
//                    SUM( IF( subsection.layers = 1 AND broodEggs > 0 AND broodHatched IS NOT NULL, result.breeders * broodHatched / broodEggs, 0 ) ) AS broodLayerHatched,
//
//                    # pigeon result, need pair and hatched
//                    SUM( IF( subsection.layers = 0 AND pairs > 0 AND broodHatched IS NOT NULL, breeders, 0 )) AS broodPigeonResultBreeders,
//                    SUM( IF( subsection.layers = 0 AND pairs > 0 AND broodHatched IS NOT NULL, result.breeders * broodHatched / pairs, 0 )) AS broodPigeonResult,
//                    # pigeon brood, need broods and hatched
//                    SUM( IF( subsection.layers = 0 AND broodEggs > 0 AND broodHatched IS NOT NULL, breeders, 0 )) AS broodPigeonEggsBreeders,
//                    SUM( IF( subsection.layers = 0 AND broodEggs > 0, broodEggs, 0 )) AS broodPigeonEggs,
//                    SUM( IF( subsection.layers = 0 AND broodHatched IS NOT NULL, result.breeders * broodHatched / broodEggs, 0 )) AS broodPigeonHatched,
//
//                    #show
//                    SUM( IF( showCount > 0, result.breeders, 0 ) ) AS showBreeders,
//                    SUM( showCount ) AS showCount,
//                    SUM( IF( showCount > 0 AND showScore >= 0, result.breeders * showScore, 0 ) ) AS showScore,
//                    subsection.layers AS layers
//
//                FROM result
//                    LEFT JOIN pair ON pair.id = result.pairId
//                    LEFT JOIN breed ON breed.id = result.breedId
//                    LEFT JOIN color ON color.id = result.colorId
//                    LEFT JOIN section AS subsection ON subsection.id = breed.sectionId
//                    LEFT JOIN section ON section.id = subsection.parentId
//
//                WHERE
//                    result.year = :year
//                    AND ( pair.id IS NULL OR pair.accepted = 1 )
//                    AND result.districtId IN (
//                        SELECT child.id
//                        FROM district AS parent
//                        LEFT JOIN district AS child
//                            ON child.id = parent.id OR child.parentId = parent.id
//                        WHERE parent.id = :districtId OR parent.parentId = :districtId
//                    )
//                    AND  ( :group IS NULL OR result.group = :group )
//
//                GROUP BY result.year, result.districtId, result.breedId, result.colorId, result.aocColor, result.group, pair.breederId
//            ) AS results
//
//            GROUP BY breedId, colorId
//            ORDER BY subsectionOrder, breedName, MAX(aocColor), colorName;
//
//        ");
//		return Query::selectArray( $stmt, $args );
//	}
//


}