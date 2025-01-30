<?php

namespace App\model;

use Error;
use Exception;

class Result extends Query
{
    public static function get( $id ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            SELECT id, pairId, districtId, `year`, `group`, sectionId, breedId, colorId, aocColor, breeders, pairs, layDames, layEggs, broodEggs, broodFertile, broodHatched, showCount, showScore
            FROM result
            WHERE id=:id
        ' );
        return Query::select( $stmt, $args );
    }

    public static function new(
        ? int $pairId, int $districtId, int $year, string $group,
        ? int $sectionId, int $breedId, ? int $colorId, ? string $aocColor,
        int $breeders, ? int $pairs,
        ? int $layDames, ? float $layEggs, ? float $layWeight,
        ? int $broodEggs, ? int $broodFertile, ? int $broodHatched,
        ? int $showCount, ? float $showScore,
        int $modifierId
    ) : ? int {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            INSERT INTO result ( pairId, districtId, `year`, `group`, sectionId, breedId, colorId, aocColor, breeders, pairs, layDames, layEggs, layWeight, broodEggs, broodFertile, broodHatched, showCount, showScore, modifierId ) 
            VALUES ( :pairId, :districtId, :year, :group, :sectionId, :breedId, :colorId, :aocColor, :breeders, :pairs, :layDames, :layEggs, :layWeight, :broodEggs, :broodFertile, :broodHatched, :showCount, :showScore, :modifierId )
        ' );
        return Query::insert( $stmt, $args );
    }


    public static function set(
        int $id, ? int $pairId, int $districtId, int $year, string $group,
		? int $sectionId, int $breedId, ? int $colorId, ? string $aocColor,
        int $breeders, ? int $pairs,
        ? int $layDames, ? float $layEggs, ? float $layWeight,
        ? int $broodEggs, ? int $broodFertile, ? int $broodHatched,
        ? int $showCount, ? float $showScore,
        int $modifierId
    ) : bool {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            UPDATE  result
            SET pairId=:pairId, districtId=:districtId, `year`=:year, `group`=:group, sectionId=:sectionId, breedId=:breedId, colorId=:colorId, aocColor=:aocColor, breeders=:breeders, pairs=:pairs, layDames=:layDames, layEggs=:layEggs, layWeight=:layWeight, broodEggs=:broodEggs, broodFertile=:broodFertile, broodHatched=:broodHatched, showCount=:showCount, showScore=:showScore, modifierId=:modifierId
            WHERE id=:id
        ' );
        return Query::update( $stmt, $args );
    }

    public static function del( int $id ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            DELETE 
            FROM result
            WHERE id=:id
        ' );
        return Query::delete( $stmt, $args );
    }


    public static function delForPair(int $pairId ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            DELETE 
            FROM result
            WHERE pairId=:pairId
        ' );
        return Query::delete( $stmt, $args );
    }

    /**
     * REPORTS, the bigguns
     */

	// for charts, one result for current district and year ( filtered for s,b,c )
    public static function getResultDistrictYear(int $districtId, int $year, ? int $sectionId, ? int $breedId, ? int $colorId, ? int $group ) : ? array {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            SELECT count(*) AS results, 
                :districtId AS districtId, :year AS `year`, :sectionId  AS sectionId, layers, :breedId AS breedId, :colorId AS colorId, :group AS `group`, 
                
                # breeders for district and distinct pair breeders,  
                CAST( SUM( breeders ) AS UNSIGNED ) AS breeders, 
                
                # lay eggs
                CAST( SUM( IF( layEggs > 0, breeders, 0 ) ) AS UNSIGNED ) AS layBreeders,  
                CAST( SUM( IF( layEggs > 0, breeders * layShould, 0 ) ) / SUM( IF( layEggs > 0, breeders, 0 ) ) AS DOUBLE ) AS layShould,  
                CAST( SUM( IF( layEggs > 0, breeders * layEggs / layShould, 0 ) ) / SUM( IF( layEggs > 0, breeders, 0 ) ) AS DOUBLE ) AS layEggs,  
                # layweight
                CAST( SUM( IF( layWeight > 0, breeders, 0 ) ) AS UNSIGNED ) AS layWeightBreeders,  
                CAST( SUM( IF( layWeight > 0, breeders * layWeightShould, 0 ) ) / SUM( IF( layWeight > 0, breeders, 0 ) ) AS DOUBLE ) AS layWeightShould, # only reported breeds 
                CAST( SUM( IF( layWeight > 0, breeders * layWeight / layWeightShould, 0 ) ) / SUM( IF( layWeight > 0, breeders, 0 ) ) AS DOUBLE ) AS layWeight,  
                # brood all
                CAST( SUM( IF( broodEggs > 0, breeders, 0 ) ) AS UNSIGNED ) AS broodBreeders,
                # brood layers
                CAST( SUM( IF( layers = 1, breeders, 0 ) ) AS UNSIGNED ) AS broodLayerBreeders,               
                CAST( SUM( IF( layers = 1, broodEggs, 0 ) ) AS UNSIGNED ) AS broodLayerEggs,  
                CAST( SUM( IF( layers = 1 AND broodFertile IS NOT NULL, breeders * broodFertile / broodEggs, 0 ) ) / SUM( IF( layers = 1 AND broodFertile IS NOT NULL, breeders, 0 ) ) AS DOUBLE ) AS broodLayerFertile,
                CAST( SUM( IF( layers = 1 AND broodHatched IS NOT NULL, breeders * broodHatched / broodEggs, 0 ) ) / SUM( IF( layers = 1 AND broodHatched IS NOT NULL, breeders, 0 ) ) AS DOUBLE ) AS broodLayerHatched,
                # brood pigeons
                CAST( SUM( IF( layers = 0 AND broodHatched IS NOT NULL, breeders, 0 ) ) AS UNSIGNED ) AS broodPigeonBreeders,                     
                CAST( SUM( IF( layers = 0, broodHatched, 0 ) ) AS UNSIGNED ) AS broodPigeonHatched,  
                CAST( SUM( IF( layers = 0 AND pairs > 0 AND broodHatched IS NOT NULL, breeders * broodHatched / pairs, 0 ) ) / SUM( IF( layers = 0 AND pairs > 0 AND broodHatched IS NOT NULL, breeders, 0 ) ) AS DOUBLE ) AS broodPigeonResult,               
                # show  
                CAST( SUM( IF( showCount > 0, breeders, 0 ) ) AS UNSIGNED ) AS showBreeders,
                CAST( SUM( showCount ) AS UNSIGNED ) AS showCount,  
                CAST( SUM( IF( showScore IS NOT NULL, breeders * showScore, 0 ) ) / SUM( IF( showScore IS NOT NULL, breeders, 0 ) ) AS DOUBLE ) AS showScore
            FROM (
                # to group the breeders results for multiple pairs as one breeder, not per pair
                SELECT 
                    result.id, result.districtId, result.year, result.breeders,
                    breed.sectionId as sectionId, result.breedId, result.colorId, result.group,                     
                    breed.layEggs AS layShould, breed.layWeight AS layWeightShould,
                    SUM( pairs ) AS pairs, SUM( layDames ) AS layDames, AVG( result.layEggs ) AS layEggs, AVG( result.layWeight ) AS layWeight, 
                    SUM( broodEggs ) AS broodEggs, SUM( broodFertile ) AS broodFertile, SUM( broodHatched ) AS broodHatched,
                    SUM( showCount ) AS showCount, AVG( showScore ) AS showScore,
                    section.layers
                FROM result
                    LEFT JOIN pair ON pair.id = result.pairId
                    LEFT JOIN breed ON breed.id = result.breedId
                    LEFT JOIN section ON section.id = breed.sectionId
                WHERE 
                    result.year = :year 
                    AND result.districtId IN ( # district or subdistrict
                        SELECT DISTINCT child.id 
                        FROM district AS parent
                        LEFT JOIN district AS child ON child.parentId=parent.id OR child.id=parent.id 
                        WHERE parent.id=:districtId OR parent.parentId=:districtId                        
                    )
                    AND ( :sectionId IS NULL OR breed.sectionId IN (
                        SELECT DISTINCT child.id 
                        FROM section AS parent 
                        LEFT JOIN section AS child ON child.parentId=parent.id OR child.id=parent.id
                        WHERE parent.id=:sectionId OR parent.parentId=:sectionId
                    ))        
                    AND ( :breedId IS NULL OR result.breedId = :breedId )
                    AND ( :colorId IS NULL OR result.colorId = :colorId )
                    AND ( :group   IS NULL OR result.group   = :group )                
                GROUP BY result.year, result.districtId, result.breedId, result.colorId, result.group, pair.breederId                
            ) AS results
            # GROUP BY result.districtId, result.year 
        ');
        return Query::select($stmt, $args); // returns null, no results found, or single result
    }

	// for results district trend
    public static function getResultsDistrictYears(int $districtId, ? int $sectionId, ? int $breedId, ? int $colorId, ? string $group ) : ? array {

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
                CAST( SUM( IF( layEggs > 0, breeders, 0 ) ) AS UNSIGNED ) AS layBreeders,  
                CAST( SUM( IF( layEggs > 0, breeders * layShould, 0 ) ) / SUM( IF( layEggs > 0, breeders, 0 ) ) AS DOUBLE ) AS layShould,  
                CAST( SUM( IF( layEggs > 0, breeders * layEggs / layShould, 0 ) ) / SUM( IF( layEggs > 0, breeders, 0 ) ) AS DOUBLE ) AS layEggs,  
                # lay weight
                CAST( SUM( IF( layWeight > 0, breeders, 0 ) ) AS UNSIGNED ) AS layWeightBreeders,  
                CAST( SUM( IF( layWeight > 0, breeders * layWeightShould, 0 ) ) / SUM( IF( results.layWeight > 0, breeders, 0 ) ) AS DOUBLE ) AS layWeightShould, 
                CAST( SUM( IF( layWeight > 0, breeders * layWeight / layWeightShould, 0 ) ) / SUM( IF( layWeight > 0, breeders, 0 ) ) AS DOUBLE ) AS layWeight,  
                # brood all
                CAST( SUM( IF( broodEggs > 0, breeders, 0 ) ) AS UNSIGNED ) AS broodBreeders,
                # brood layers
                CAST( SUM( IF( layers = 1 AND broodEggs > 0, breeders, 0 ) ) AS UNSIGNED ) AS broodLayerBreeders,
                CAST( SUM( IF( layers = 1, broodEggs, 0 ) ) AS UNSIGNED ) AS broodLayerEggs,  
                CAST( SUM( IF( layers = 1 AND broodFertile IS NOT NULL, breeders * broodFertile / broodEggs, 0 ) ) / SUM( IF( layers = 1 AND broodFertile IS NOT NULL, results.breeders, 0 ) ) AS DOUBLE ) AS broodLayerFertile,
                CAST( SUM( IF( layers = 1 AND broodHatched IS NOT NULL, breeders * broodHatched / broodEggs, 0 ) ) / SUM( IF( layers = 1 AND broodHatched IS NOT NULL, breeders, 0 ) ) AS DOUBLE ) AS broodLayerHatched,
                # brood pigeons
                CAST( SUM( IF( layers = 0 AND broodHatched IS NOT NULL, breeders, 0 ) ) AS UNSIGNED ) AS broodPigeonBreeders,
                CAST( SUM( IF( layers = 0, broodHatched, 0 ) ) AS UNSIGNED ) AS broodPigeonHatched,  
                CAST( SUM( IF( layers = 0 AND pairs > 0 AND broodHatched IS NOT NULL, breeders * broodHatched / pairs, 0 ) ) / SUM( IF( layers = 0 AND pairs > 0 AND broodHatched IS NOT NULL, breeders, 0 ) ) AS DOUBLE ) AS broodPigeonResult,               
                # show  
                CAST( SUM( IF( showCount > 0, breeders, 0 ) ) AS UNSIGNED ) AS showBreeders,
                CAST( SUM( showCount ) AS UNSIGNED ) AS showCount,  
                CAST( SUM( IF( showScore IS NOT NULL, breeders * showScore, 0 ) ) / SUM( IF( showScore IS NOT NULL, breeders, 0 ) ) AS DOUBLE ) AS showScore
            
            FROM ( # generate years
                SELECT @year:=YEAR( NOW() )+1
            ) AS init, (
                SELECT @year:=@year-1 AS `year`
                FROM color
                WHERE @year > :startYear
            ) AS years
                
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
                    GROUP BY result.year, result.districtId, result.breedId, result.colorId, result.group, pair.breederId                                
                ) AS results ON results.year = years.year                 
          
            GROUP BY years.year
            ORDER BY years.year
        ');
        return Query::selectArray($stmt, $args); // returns null, no results found, or single result
    }

	// for results year districts map
    public static function getResultsYearDistricts(int $year, ? int $sectionId, ? int $breedId, ? int $colorId, ? string $group ) : ? array {
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
                CAST( SUM( IF( broodEggs > 0, breeders, 0 ) ) AS UNSIGNED ) AS broodBreeders,
                # brood layers
                CAST( SUM( IF( layers = 1 AND broodEggs > 0, breeders, 0 ) ) AS UNSIGNED ) AS broodLayerBreeders,
                CAST( SUM( IF( layers = 1, broodEggs, 0 ) ) AS UNSIGNED ) AS broodLayerEggs,  
                CAST( SUM( IF( layers = 1 AND broodFertile IS NOT NULL, breeders * broodFertile / broodEggs, 0 ) ) / SUM( IF( layers = 1 AND broodFertile IS NOT NULL, results.breeders, 0 ) ) AS DOUBLE ) AS broodLayerFertile,
                CAST( SUM( IF( layers = 1 AND broodHatched IS NOT NULL, breeders * broodHatched / broodEggs, 0 ) ) / SUM( IF( layers = 1 AND broodHatched IS NOT NULL, breeders, 0 ) ) AS DOUBLE ) AS broodLayerHatched,
                # brood pigeons
                CAST( SUM( IF( layers = 0 AND broodHatched IS NOT NULL, breeders, 0 ) ) AS UNSIGNED ) AS broodPigeonBreeders,
                CAST( SUM( IF( layers = 0, broodHatched, 0 ) ) AS UNSIGNED ) AS broodPigeonHatched,  
                CAST( SUM( IF( layers = 0 AND pairs > 0 AND broodHatched IS NOT NULL, breeders * broodHatched / pairs, 0 ) ) / SUM( IF( layers = 0 AND pairs > 0 AND broodHatched IS NOT NULL, breeders, 0 ) ) AS DOUBLE ) AS broodPigeonResult,               
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

	// for use in district year report
	public static function getResultsDistrictYear(int $districtId, int $year ) : ? array {
		$args = get_defined_vars();
		$stmt = Query::prepare("
            SELECT COUNT(*),
                :districtId AS districtId, :year AS `year`, 
                sectionId, sectionName, sectionOrder, layers,
                subsectionId, subsectionName, subsectionOrder, 
                id AS resultId, breedId, breedName, colorId, colorName, aocColor,

                # breeders for district and breeder results
                CAST( SUM( breeders ) AS UNSIGNED ) AS breeders, 
                # pairs for pigeons       
                CAST( SUM( pairs ) AS UNSIGNED ) AS pairs,
                # lay dames
                CAST( SUM( layDames ) AS UNSIGNED ) AS layDames, # TODO needed for now, should go
                # lay eggs
                CAST( SUM( IF( layEggs > 0, breeders, 0 ) ) AS UNSIGNED ) AS layBreeders,  
                CAST( SUM( IF( layEggs > 0, breeders * layShould, 0 ) ) / SUM( IF( layEggs > 0, breeders, 0 ) ) AS DOUBLE ) AS layShould,  
                CAST( SUM( IF( layEggs > 0, breeders * layEggs / layShould, 0 ) ) / SUM( IF( layEggs > 0, breeders, 0 ) ) AS DOUBLE ) AS layEggs,  
                # layweight
                CAST( SUM( IF( layWeight > 0, breeders, 0 ) ) AS UNSIGNED ) AS layWeightBreeders,  
                CAST( SUM( IF( layWeight > 0, breeders * layWeightShould, 0 ) )             / SUM( IF( layWeight > 0, breeders, 0 ) ) AS DOUBLE ) AS layWeightShould, 
                CAST( SUM( IF( layWeight > 0, breeders * layWeight / layWeightShould, 0 ) ) / SUM( IF( layWeight > 0, breeders, 0 ) ) AS DOUBLE ) AS layWeight,  
                # brood all
                CAST( SUM( IF( broodEggs > 0, breeders, 0 ) ) AS UNSIGNED ) AS broodBreeders,
                # brood layers               
                CAST( SUM( IF( layers = 1 AND broodEggs > 0, breeders, 0 ) ) AS UNSIGNED ) AS broodLayerBreeders,
                CAST( SUM( IF( layers = 1, broodEggs, 0 ) ) AS UNSIGNED ) AS broodLayerEggs,  
                CAST( SUM( IF( layers = 1 AND broodFertile IS NOT NULL, breeders * broodFertile / broodEggs, 0 ) ) / SUM( IF( layers = 1 AND broodFertile IS NOT NULL, breeders, 0 ) ) AS DOUBLE ) AS broodLayerFertile,
                CAST( SUM( IF( layers = 1 AND broodHatched IS NOT NULL, breeders * broodHatched / broodEggs, 0 ) ) / SUM( IF( layers = 1 AND broodHatched IS NOT NULL, breeders, 0 ) ) AS DOUBLE ) AS broodLayerHatched,
                # brood pigeons
                CAST( SUM( IF( layers = 0 AND broodHatched IS NOT NULL, breeders, 0 ) ) AS UNSIGNED ) AS broodPigeonBreeders,            
                CAST( SUM( IF( layers = 0, broodHatched, 0 ) ) AS UNSIGNED ) AS broodPigeonHatched,  
                CAST( SUM( IF( layers = 0 AND pairs > 0 AND broodHatched IS NOT NULL, breeders * broodHatched / pairs, 0 ) ) / SUM( IF( layers = 0 AND pairs > 0 AND broodHatched IS NOT NULL, breeders, 0 ) ) AS DOUBLE ) AS broodPigeonResult,               
                # show  
                CAST( SUM( IF( showCount > 0, breeders, 0 ) ) AS UNSIGNED ) AS showBreeders,
                CAST( SUM( showCount ) AS UNSIGNED ) AS showCount,  
                CAST( SUM( IF( showScore IS NOT NULL, breeders * showScore, 0 ) ) / SUM( IF( showScore IS NOT NULL, breeders, 0 ) ) AS DOUBLE ) AS showScore            
            FROM (
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
                    AND result.districtId IN ( # also get subdistricts
                        SELECT child.id FROM district AS parent
                            LEFT JOIN district AS child ON child.id = parent.id OR child.parentId = parent.id
                        WHERE parent.id=:districtId OR parent.parentId = :districtId                
                    )
                GROUP BY result.year, result.districtId, result.breedId, result.colorId, result.aocColor, result.group, pair.breederId                                
            ) AS results            
            
            GROUP BY subsectionOrder, breedName, colorName
            ORDER BY subsectionOrder, breedName, MAX(aocColor), colorName
        ");

		return Query::selectArray( $stmt, $args );
	}

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

	// new version 2 getters

	/* returns the district result for year and color */
	public static function forColor( int $districtId, int $year, int $colorId ) : array {
		$args = get_defined_vars();
		$stmt = Query::prepare('
			SELECT * FROM result WHERE districtId=:districtId AND `year`=:year AND colorId=:colorId ORDER BY pairId
        ');
		return Query::selectArray($stmt, $args);
	}
}