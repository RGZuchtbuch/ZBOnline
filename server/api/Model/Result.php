<?php

namespace App\Model;

use App\Config;
use Exception;

class Result
{
    public static function get( $id ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            SELECT id, reportId, districtId, `year`, `group`, breedId, colorId, breeders, pairs, layDames, layEggs, broodEggs, broodFertile, broodHatched, showCount, showScore
            FROM result
            WHERE id=:id
        ' );
        return Query::selectArray( $stmt, $args );
    }

    public static function new(
        $reportId, $districtId, $year, $group,
        $breedId, $colorId,
        $breeders, $pairs,
        $layDames, $layEggs, $layWeight,
        $broodEggs, $broodFertile, $broodHatched,
        $showCount, $showScore,
        $modifier
    ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            INSERT INTO result ( reportId, districtId, `year`, `group`, breedId, colorId, breeders, pairs, layDames, layEggs, layWeight, broodEggs, broodFertile, broodHatched, showCount, showScore, modifier ) 
            VALUES ( :reportId, :districtId, :year, :group, :breedId, :colorId, :breeders, :pairs, :layDames, :layEggs, :layWeight, :broodEggs, :broodFertile, :broodHatched, :showCount, :showScore, :modifier )
        ' );
        return Query::insert( $stmt, $args );
    }
    public static function set(
        $id, $reportId, $districtId, $year, $group,
        $breedId, $colorId,
        $breeders, $pairs,
        $layDames, $layEggs, $layWeight,
        $broodEggs, $broodFertile, $broodHatched,
        $showCount, $showScore,
        $modifier
    ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            UPDATE  result
            SET reportId=:reportId, districtId=:districtId, `year`=:year, `group`=:group, breedId=:breedId, colorId=:colorId, breeders=:breeders, pairs=:pairs, layDames=:layDames, layEggs=:layEggs, layWeight=:layWeight, broodEggs=:broodEggs, broodFertile=:broodFertile, broodHatched=:broodHatched, showCount=:showCount, showScore=:showScore, modifier=:modifier
            WHERE id=:id
        ' );
        return Query::update( $stmt, $args );
    }

    public static function delete( $id ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            DELETE 
            FROM result
            WHERE id=:id
        ' );
        return Query::delete( $stmt, $args );
    }

    public static function districtsForColor( $year, $colorId ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( " 
            SELECT 
                COUNT(*) AS `count`, results.colorId, 
                district.id, district.name, district.latitude, district.longitude,
                
                CAST( SUM( breeders ) AS UNSIGNED ) AS breeders, CAST( SUM( pairs ) AS UNSIGNED) AS pairs,                
                CAST( COUNT( DISTINCT breedId ) AS UNSIGNED) AS breeds, CAST( COUNT( DISTINCT colorId ) AS UNSIGNED) AS colors, 	# could both be just 1 !	
                
                SUM( layDames) AS layDames, 
                SUM( IF( layEggs, pairs * layEggs, NULL ) ) / SUM( IF( layEggs, pairs, NULL ) ) AS layEggs,
                SUM( IF( layWeight, pairs * layWeight, NULL ) ) / SUM( IF( layWeight, pairs, NULL ) ) AS layWeight,
                
                CAST( SUM( broodEggs ) AS UNSIGNED) AS broodEggs, 
                CAST( SUM( broodFertile ) AS UNSIGNED) AS broodFertile, 
                CAST( SUM( broodHatched ) AS UNSIGNED) AS broodHatched,
                
                CAST( SUM( showCount ) AS UNSIGNED) AS showCount, 
                SUM( IF( showScore, pairs * showScore, NULL ) ) / SUM( IF( showScore, pairs, NULL ) ) AS showScore
            FROM district
            LEFT JOIN (
                SELECT  result.*, district.rootId 
                FROM result 
                    LEFT JOIN district ON district.id=result.districtId
                WHERE `year`=:year AND colorId=:colorId               
            ) AS results ON results.rootId=district.id
            GROUP BY district.rootId
        " );
        return Query::selectArray( $stmt, $args );
    }
    public static function districtsForBreed( $year, $breedId ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( " 
            SELECT 
                COUNT(*) AS `count`, results.colorId, 
                district.id, district.name, district.latitude, district.longitude,
                
                CAST( SUM( breeders ) AS UNSIGNED ) AS breeders, CAST( SUM( pairs ) AS UNSIGNED) AS pairs,                
                CAST( COUNT( DISTINCT breedId ) AS UNSIGNED) AS breeds, CAST( COUNT( DISTINCT colorId ) AS UNSIGNED) AS colors, 	# could both be just 1 !	
                
                SUM( layDames) AS layDames, 
                SUM( IF( layEggs, pairs * layEggs, NULL ) ) / SUM( IF( layEggs, pairs, NULL ) ) AS layEggs,
                SUM( IF( layWeight, pairs * layWeight, NULL ) ) / SUM( IF( layWeight, pairs, NULL ) ) AS layWeight,
                
                CAST( SUM( broodEggs ) AS UNSIGNED) AS broodEggs, 
                CAST( SUM( broodFertile ) AS UNSIGNED) AS broodFertile, 
                CAST( SUM( broodHatched ) AS UNSIGNED) AS broodHatched,
                
                CAST( SUM( showCount ) AS UNSIGNED) AS showCount, 
                SUM( IF( showScore, pairs * showScore, NULL ) ) / SUM( IF( showScore, pairs, NULL ) ) AS showScore
            FROM district
            LEFT JOIN (
                SELECT  result.*, district.rootId 
                FROM result 
                    LEFT JOIN district ON district.id=result.districtId
                WHERE `year`=:year AND breedId=:breedId               
            ) AS results ON results.rootId=district.id
            GROUP BY district.rootId
        " );

        return Query::selectArray( $stmt, $args );
    }

    public static function districtsForSection( $year, $sectionId ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( "
            SELECT 
                COUNT(*) AS `count`, 
                district.rootId AS id, district.name, district.latitude, district.longitude, avg( results.year),
            
                CAST( SUM( breeders ) AS UNSIGNED ) AS breeders, CAST( SUM( pairs ) AS UNSIGNED) AS pairs,                
                CAST( COUNT( DISTINCT breedId ) AS UNSIGNED) AS breeds, CAST( COUNT( DISTINCT colorId ) AS UNSIGNED) AS colors, 	# could both be just 1 !	
                SUM( layDames) AS layDames, 
                SUM( IF( layEggs, pairs * layEggs, NULL ) ) / SUM( IF( layEggs, pairs, NULL ) ) AS layEggs, SUM( IF( layWeight, pairs * layWeight, NULL ) ) / SUM( IF( layWeight, pairs, NULL ) ) AS layWeight,
                CAST( SUM( broodEggs ) AS UNSIGNED) AS broodEggs, CAST( SUM( broodFertile ) AS UNSIGNED) AS broodFertile, CAST( SUM( broodHatched ) AS UNSIGNED) AS broodHatched,
                CAST( SUM( showCount ) AS UNSIGNED) AS showCount, SUM( IF( showScore, pairs * showScore, NULL ) ) / SUM( IF( showScore, pairs, NULL ) ) AS showScore            
            FROM district 
            
            LEFT JOIN (
                SELECT breed.sectionId, result.*
                FROM result 
                    LEFT JOIN breed ON breed.id = result.breedId	
                WHERE result.year=:year
            ) AS results ON results.districtId = district.id AND results.sectionId IN (
                SELECT id
                FROM (
                    SELECT @parents:=:sectionId
                ) AS init,  (
                    SELECT parentId, id FROM section 
                    WHERE ( FIND_IN_SET( parentId, @parents ) AND @parents:=CONCAT( @parents, ',', id ) ) OR id=:sectionId
                ) AS list
            )
            GROUP BY district.rootId      
        " );

        return Query::selectArray($stmt, $args );
    }


    public static function yearsForColor( $districtId, $colorId ) {
        $startYear = Config::START_YEAR;
        $args = get_defined_vars();
        $stmt = Query::prepare( "
            SELECT years.year, result.colorId,
                CAST( SUM( breeders ) AS UNSIGNED ) AS breeders, CAST( SUM( pairs ) AS UNSIGNED) AS pairs,                
                CAST( COUNT( DISTINCT breedId ) AS UNSIGNED) AS breeds, CAST( COUNT( DISTINCT colorId ) AS UNSIGNED) AS colors, 	# could both be just 1 !	
                SUM( layDames) AS layDames, SUM( IF( layEggs, pairs * layEggs, NULL ) ) / SUM( IF( layEggs, pairs, NULL ) ) AS layEggs, SUM( IF( layWeight, pairs * layWeight, NULL ) ) / SUM( IF( layWeight, pairs, NULL ) ) AS layWeight,
                CAST( SUM( broodEggs ) AS UNSIGNED) AS broodEggs, CAST( SUM( broodFertile ) AS UNSIGNED) AS broodFertile, CAST( SUM( broodHatched ) AS UNSIGNED) AS broodHatched,
                CAST( SUM( showCount ) AS UNSIGNED) AS showCount, SUM( IF( showScore, pairs * showScore, NULL ) ) / SUM( IF( showScore, pairs, NULL ) ) AS showScore
            
            FROM (
                SELECT @year:=YEAR( NOW() )+1
            ) AS init, (
                SELECT @year:=@year-1 AS `year`
                FROM color
                WHERE @year > :startYear
            ) AS years
            
            LEFT JOIN result ON result.year = years.year AND result.colorId=:colorId
                AND result.districtId IN (
                    SELECT id
                    FROM (
                        SELECT @parents:=:districtId
                    ) AS init, (
                        SELECT rootId, id, parentId FROM district ORDER BY parentId, id
                    ) AS sorted
                    WHERE (FIND_IN_SET(parentId, @parents) > 0 AND @parents:=CONCAT(@parents, ',', id)) OR id=:districtId
                    
                )
                        
            GROUP BY years.year
            ORDER BY years.year
        " );

/*
        $stmt = Query::prepare( '       
            SELECT  results.*, members.members
            
            # first get grouped results for all years (2000-now)
            FROM (
                # start with list of years as we want results per year
               WITH RECURSIVE years(`year`) AS (
                   SELECT :startYear AS `year`
                   UNION
                   SELECT `year`+1 AS `year` FROM years
                   WHERE `year` < YEAR( NOW())
               )
               SELECT 
                    years.year AS `year`, result.districtId AS districtId,
                    CAST( IFNULL( SUM( breeders ), 0 ) AS UNSIGNED ) AS breeders, 
                    CAST( IFNULL( SUM( pairs ), 0 ) AS UNSIGNED ) AS pairs, 
                    CAST( IFNULL( COUNT( DISTINCT breedId ), 0 ) AS  UNSIGNED ) AS breeds,
                    CAST( IFNULL( COUNT( DISTINCT colorId ), 0 ) AS  UNSIGNED ) AS colors,
                    CAST( IFNULL( SUM( layDames), 0 ) AS UNSIGNED) AS layDames, IFNULL( AVG( layEggs ), 0 ) AS layEggs, IFNULL( AVG( layWeight ), 0 ) AS layWeight,
                    CAST( IFNULL( SUM( broodEggs ), 0 ) AS UNSIGNED) AS broodEggs, CAST( IFNULL( SUM( broodFertile ), 0 ) AS UNSIGNED) AS broodFertile, CAST( IFNULL( SUM( broodHatched ), 0 ) AS UNSIGNED) AS broodHatched,
                    CAST( IFNULL( SUM( showCount ), 0 ) AS UNSIGNED) AS showCount, IFNULL( AVG( showScore), 0 ) AS showScore		
                FROM years
            
                # add results for year and check for wanted district and subdistricts    
                LEFT JOIN result ON result.year = years.year
                AND result.colorId = :colorId
                AND result.districtId IN ( # check is in district or a child district
                    WITH RECURSIVE districts( parentId, id, latitude, longitude, name ) AS (
                        SELECT id, id, latitude, longitude, name FROM district WHERE id=:districtId
                        UNION
                        SELECT districts.id, district.id, districts.latitude, districts.longitude, districts.name
                        FROM districts JOIN district ON district.parentId=districts.id
                    )
                    SELECT id FROM districts  	
                )
                GROUP BY years.year
            ) AS results
            
            # add members from user
            LEFT JOIN (
               WITH RECURSIVE years(year) AS (
                   SELECT 2000 AS `year` 
                   UNION
                   SELECT `year`+1 AS `year` FROM years
                   WHERE `year` < YEAR( NOW())
               )
                SELECT years.year AS `year`, CAST( COUNT( DISTINCT user.id ) AS UNSIGNED ) AS members
                FROM years
                LEFT JOIN user 
                    ON YEAR( user.start ) <= years.year AND ( user.end IS NULL OR YEAR( user.end ) >= years.year )
                	AND user.clubId IN ( # check if in district or a child district
                        WITH RECURSIVE districts( parentId, id, latitude, longitude, name ) AS (
                            SELECT id, id, latitude, longitude, name FROM district WHERE id=:districtId
                            UNION
                            SELECT districts.id, district.id, districts.latitude, districts.longitude, districts.name
                            FROM districts JOIN district ON district.parentId=districts.id
                        )
                        SELECT id FROM districts  	
                    )

                GROUP BY years.year
            ) AS members ON members.year = results.year
    ' );
*/
        return Query::selectArray( $stmt, $args );
    }
    public static function yearsForBreed( $districtId, $breedId ) {
        $startYear = Config::START_YEAR;
        $args = get_defined_vars();
        $stmt = Query::prepare( "
            SELECT years.year, result.colorId,
                CAST( SUM( breeders ) AS UNSIGNED ) AS breeders, CAST( SUM( pairs ) AS UNSIGNED) AS pairs,                
                CAST( COUNT( DISTINCT breedId ) AS UNSIGNED) AS breeds, CAST( COUNT( DISTINCT colorId ) AS UNSIGNED) AS colors, 	# could both be just 1 !	
                SUM( layDames) AS layDames, SUM( IF( layEggs, pairs * layEggs, NULL ) ) / SUM( IF( layEggs, pairs, NULL ) ) AS layEggs, SUM( IF( layWeight, pairs * layWeight, NULL ) ) / SUM( IF( layWeight, pairs, NULL ) ) AS layWeight,
                CAST( SUM( broodEggs ) AS UNSIGNED) AS broodEggs, CAST( SUM( broodFertile ) AS UNSIGNED) AS broodFertile, CAST( SUM( broodHatched ) AS UNSIGNED) AS broodHatched,
                CAST( SUM( showCount ) AS UNSIGNED) AS showCount, SUM( IF( showScore, pairs * showScore, NULL ) ) / SUM( IF( showScore, pairs, NULL ) ) AS showScore
            FROM (
                SELECT @year:=YEAR( NOW() )+1
            ) AS init, (
                SELECT @year:=@year-1 AS `year`
                FROM color
                WHERE @year > :startYear
            ) AS years
            
            LEFT JOIN result ON result.year = years.year AND result.breedId=:breedId
                AND result.districtId IN (
                    SELECT id
                    FROM (
                        SELECT @root:=:districtId, @parents:=@root
                    ) AS init, (
                        SELECT rootId, id, parentId FROM district ORDER BY parentId, id
                    ) AS sorted
                    WHERE (FIND_IN_SET(parentId, @parents) > 0 AND @parents:=CONCAT(@parents, ',', id)) OR id = @root
                    
                )
                        
            GROUP BY years.year
            ORDER BY years.year
        " );

/*
        $stmt = Query::prepare( '       
            SELECT  results.*, members.members
           
            # first get grouped results for all years (2000-now)
            FROM (
                # start with list of years as we want results per year
               WITH RECURSIVE years(`year`) AS (
                   SELECT 2000 AS `year` 
                   UNION
                   SELECT `year`+1 AS `year` FROM years
                   WHERE `year` < YEAR( NOW())
               )
               SELECT 
                    years.year AS `year`, result.districtId AS districtId,
                    CAST( IFNULL( SUM( breeders ), 0 ) AS UNSIGNED ) AS breeders, 
                    CAST( IFNULL( SUM( pairs ), 0 ) AS UNSIGNED ) AS pairs, 
                    CAST( IFNULL( COUNT( DISTINCT breedId ), 0 ) AS  UNSIGNED ) AS breeds,
                    CAST( IFNULL( COUNT( DISTINCT colorId ), 0 ) AS  UNSIGNED ) AS colors,
                    CAST( IFNULL( SUM( layDames), 0 ) AS UNSIGNED) AS layDames, IFNULL( AVG( layEggs ), 0 ) AS layEggs, IFNULL( AVG( layWeight ), 0 ) AS layWeight,
                    CAST( IFNULL( SUM( broodEggs ), 0 ) AS UNSIGNED) AS broodEggs, CAST( IFNULL( SUM( broodFertile ), 0 ) AS UNSIGNED) AS broodFertile, CAST( IFNULL( SUM( broodHatched ), 0 ) AS UNSIGNED) AS broodHatched,
                    CAST( IFNULL( SUM( showCount ), 0 ) AS UNSIGNED) AS showCount, IFNULL( AVG( showScore), 0 ) AS showScore		
                FROM years
            
                # add results for year and check for wanted district and subdistricts    
                LEFT JOIN result ON result.year = years.year
                AND result.breedId = :breedId
                AND result.districtId IN ( # check is in district or a child district
                    WITH RECURSIVE districts( parentId, id, latitude, longitude, name ) AS (
                        SELECT id, id, latitude, longitude, name FROM district WHERE id=:districtId
                        UNION
                        SELECT districts.id, district.id, districts.latitude, districts.longitude, districts.name
                        FROM districts JOIN district ON district.parentId=districts.id
                    )
                    SELECT id FROM districts  	
                )
                GROUP BY years.year
            ) AS results
            
            # add members from user
            LEFT JOIN (
               WITH RECURSIVE years(year) AS (
                   SELECT 2000 AS `year` 
                   UNION
                   SELECT `year`+1 AS `year` FROM years
                   WHERE `year` < YEAR( NOW())
               )
                SELECT years.year AS `year`, CAST( COUNT( DISTINCT user.id ) AS UNSIGNED ) AS members
                FROM years
                LEFT JOIN user 
                    ON YEAR( user.start ) <= years.year AND ( user.end IS NULL OR YEAR( user.end ) >= years.year )
                	AND user.clubId IN ( # check if in district or a child district
                        WITH RECURSIVE districts( parentId, id, latitude, longitude, name ) AS (
                            SELECT id, id, latitude, longitude, name FROM district WHERE id=:districtId
                            UNION
                            SELECT districts.id, district.id, districts.latitude, districts.longitude, districts.name
                            FROM districts JOIN district ON district.parentId=districts.id
                        )
                        SELECT id FROM districts  	
                    )

                GROUP BY years.year
            ) AS members ON members.year = results.year 
        ' );
*/
        return Query::selectArray( $stmt, $args );
    }
    public static function yearsForSection( $districtId, $sectionId ) {
        $startYear = Config::START_YEAR;
        $args = get_defined_vars();
        $stmt = Query::prepare( "            
            SELECT years.year,
                CAST( SUM( breeders ) AS UNSIGNED ) AS breeders, CAST( SUM( pairs ) AS UNSIGNED) AS pairs,                
                CAST( COUNT( DISTINCT breedId ) AS UNSIGNED) AS breeds, CAST( COUNT( DISTINCT colorId ) AS UNSIGNED) AS colors, 	# could both be just 1 !	
                SUM( layDames) AS layDames, SUM( IF( layEggs, pairs * layEggs, NULL ) ) / SUM( IF( layEggs, pairs, NULL ) ) AS layEggs, SUM( IF( layWeight, pairs * layWeight, NULL ) ) / SUM( IF( layWeight, pairs, NULL ) ) AS layWeight,
                CAST( SUM( broodEggs ) AS UNSIGNED) AS broodEggs, CAST( SUM( broodFertile ) AS UNSIGNED) AS broodFertile, CAST( SUM( broodHatched ) AS UNSIGNED) AS broodHatched,
                CAST( SUM( showCount ) AS UNSIGNED) AS showCount, SUM( IF( showScore, pairs * showScore, NULL ) ) / SUM( IF( showScore, pairs, NULL ) ) AS showScore
            
            FROM (
                SELECT @year:=YEAR( NOW() )+1
            ) AS init, (
                SELECT @year:=@year-1 AS `year`
                FROM color
                WHERE @YEAR > :startYear
            ) AS years
            
            LEFT JOIN (
            	SELECT result.*
            	FROM result
				LEFT JOIN breed ON breed.id=result.breedId
				WHERE result.districtId IN (
	                    SELECT id
	                    FROM (
	                        SELECT @districtRoot:=:districtId, @districts:=:districtId
	                    ) AS init, (
                            SELECT id, parentId FROM district 
                            ORDER BY parentId, id
	                    ) AS sorted
						WHERE (FIND_IN_SET(parentId, @districts) > 0 AND @districts:=CONCAT(@districts, ',', id)) OR id = @districtRoot 
	                )
	                AND breed.sectionId IN (
						SELECT id FROM (
							SELECT @sectionRoot:=:sectionId, @sections:=:sectionId
						) AS init, (
							SELECT id, parentId FROM section 
							ORDER BY parentId, id
						) AS sectionList
						WHERE ( FIND_IN_SET( parentId, @sections ) AND @sections:=CONCAT( @sections, ',', id ) ) OR id=@sectionRoot
	                )           
			) AS results ON results.year = years.year 
                
            GROUP BY years.year
            ORDER BY years.year
        " );

/*
        $stmt = Query::prepare( '
            SELECT  results.*, members.members
            
            # first get grouped results for all years (2000-now)
            FROM (
                # start with list of years as we want results per year
               WITH RECURSIVE years(`year`) AS (
                   SELECT 2000 AS `year` 
                   UNION
                   SELECT `year`+1 AS `year` FROM years
                   WHERE `year` < YEAR( NOW())
               )
               SELECT 
                    years.year AS `year`, result.districtId AS districtId,
                    CAST( IFNULL( SUM( breeders ), 0 ) AS UNSIGNED ) AS breeders, 
                    CAST( IFNULL( SUM( pairs ), 0 ) AS UNSIGNED ) AS pairs, 
                    CAST( IFNULL( COUNT( DISTINCT breedId ), 0 ) AS  UNSIGNED ) AS breeds,
                    CAST( IFNULL( COUNT( DISTINCT colorId ), 0 ) AS  UNSIGNED ) AS colors,
                    CAST( IFNULL( SUM( layDames), 0 ) AS UNSIGNED) AS layDames, IFNULL( AVG( layEggs ), 0 ) AS layEggs, IFNULL( AVG( layWeight ), 0 ) AS layWeight,
                    CAST( IFNULL( SUM( broodEggs ), 0 ) AS UNSIGNED) AS broodEggs, CAST( IFNULL( SUM( broodFertile ), 0 ) AS UNSIGNED) AS broodFertile, CAST( IFNULL( SUM( broodHatched ), 0 ) AS UNSIGNED) AS broodHatched,
                    CAST( IFNULL( SUM( showCount ), 0 ) AS UNSIGNED) AS showCount, IFNULL( AVG( showScore), 0 ) AS showScore		
                FROM years
            
                # add result per year. each single result has equal weight in the calculations
                LEFT JOIN result ON result.year = years.year
                AND result.breedId IN ( # check if Breed IN section OR subsection 
                    WITH RECURSIVE sections( parentId, id ) AS (
                        SELECT id, id FROM section WHERE id=:sectionId
                        UNION
                        SELECT sections.id, section.id
                        FROM sections JOIN section ON section.parentId=sections.id
                    )
                    SELECT Breed.id FROM Breed, sections
                    WHERE Breed.sectionId = sections.id		
                )
                AND result.districtId IN ( # check if in district or a child district
                    WITH RECURSIVE districts( parentId, id, latitude, longitude, name ) AS (
                        SELECT id, id, latitude, longitude, name FROM district WHERE id=:districtId
                        UNION
                        SELECT districts.id, district.id, districts.latitude, districts.longitude, districts.name
                        FROM districts JOIN district ON district.parentId=districts.id
                    )
                    SELECT id FROM districts  	
                )
                GROUP BY years.year
            ) AS results
            
            # add members from user
            LEFT JOIN (
               WITH RECURSIVE years(year) AS (
                   SELECT 2000 AS `year` 
                   UNION
                   SELECT `year`+1 AS `year` FROM years
                   WHERE `year` < YEAR( NOW())
               )
                SELECT years.year AS `year`, CAST( COUNT( DISTINCT user.id ) AS UNSIGNED ) AS members
                FROM years
                LEFT JOIN user 
                ON YEAR( user.start ) <= years.year AND ( user.end IS NULL OR YEAR( user.end ) >= years.year )
                AND user.clubId IN ( # check if in district or a child district
                    WITH RECURSIVE districts( parentId, id, latitude, longitude, name ) AS (
                        SELECT id, id, latitude, longitude, name FROM district WHERE id=:districtId
                        UNION
                        SELECT districts.id, district.id, districts.latitude, districts.longitude, districts.name
                        FROM districts JOIN district ON district.parentId=districts.id
                    )
                    SELECT id FROM districts  	
                )
                
                GROUP BY years.year
            ) AS members ON members.year = results.year
        ' );
*/
        return Query::selectArray( $stmt, $args );
    }

}