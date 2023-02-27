<?php

namespace App\Model;

class Result
{

    public static function districtsForColor( $year, $colorId ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( '           
            SELECT results.*, members.members
            # first get results per district
            FROM (
                # start with all districts
                WITH RECURSIVE districts( parentId, id, latitude, longitude, name ) AS (
                    SELECT id, id, latitude, longitude, name FROM district WHERE parentId=1 # all bdrg children
                    UNION
                    SELECT districts.parentId, district.id, null, null, null # add child, no details needed
                    FROM districts JOIN district ON district.parentId=districts.id
                )
            
                # get all data from results per district
                SELECT 
                    `year`,
                    districts.id, districts.latitude, districts.longitude, districts.name,
                    CAST( IFNULL( SUM( breeders ), 0 ) AS UNSIGNED ) AS breeders,
                    CAST( IFNULL( SUM( pairs ), 0 ) AS UNSIGNED) AS pairs, 
                    CAST( IFNULL( COUNT( DISTINCT breedId ), 0 ) AS UNSIGNED) AS breeds, CAST( IFNULL( COUNT( DISTINCT colorId ), 0 ) AS UNSIGNED) AS colors, 		
                    CAST( IFNULL( SUM( layDames), 0 ) AS UNSIGNED) AS layDames, IFNULL( AVG( layEggs ), 0 ) AS layEggs, IFNULL( AVG( layWeight ), 0 ) AS layWeight,
                    CAST( IFNULL( SUM( broodEggs ), 0 ) AS UNSIGNED) AS broodEggs, CAST( IFNULL( SUM( broodFertile ), 0 ) AS UNSIGNED) AS broodFertile, CAST( IFNULL( SUM( broodHatched ), 0 ) AS UNSIGNED) AS broodHatched,
                    CAST( IFNULL( SUM( showCount ), 0 ) AS UNSIGNED) AS showCount, IFNULL( AVG( showScore), 0 ) AS showScore	
               
                FROM districts
                
                # add results per district
                LEFT JOIN result ON result.districtId = districts.id 
                    AND result.year = :year # the year to get data for
                    AND result.colorId = :colorId		
                GROUP BY districts.parentId
                ORDER BY districts.name
            ) AS results
            
            # add users per LV district or its subs
            LEFT JOIN (
                WITH RECURSIVE districts( parentId, id, latitude, longitude, name ) AS (
                    SELECT id, id, latitude, longitude, name FROM district WHERE parentId=1 # all bdrg children
                    UNION
                    SELECT districts.parentId, district.id, null, null, null # add child, no details needed
                    FROM districts JOIN district ON district.parentId=districts.id
                )
                SELECT districts.id AS districtId, CAST( COUNT( DISTINCT user.id ) AS UNSIGNED ) AS members
                FROM districts
                LEFT JOIN user 
                    ON YEAR( user.start ) <= :year AND ( user.`end` IS NULL OR YEAR( user.end ) >= :year )
                    AND user.clubId = districts.id
                    
                GROUP BY districts.parentId
            
            ) AS members ON members.districtId = results.id
        ' );
        return Query::selectArray( $stmt, $args );
    }
    public static function districtsForBreed( $year, $breedId ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( '           
            SELECT results.*, members.members
            # first get results per district
            FROM (
                # start with all districts
                WITH RECURSIVE districts( parentId, id, latitude, longitude, name ) AS (
                    SELECT id, id, latitude, longitude, name FROM district WHERE parentId=1 # all bdrg children
                    UNION
                    SELECT districts.parentId, district.id, null, null, null # add child, no details needed
                    FROM districts JOIN district ON district.parentId=districts.id
                )
            
                # get all data from results per district
                SELECT 
                    `year`,
                    districts.id, districts.latitude, districts.longitude, districts.name,
                    CAST( IFNULL( SUM( breeders ), 0 ) AS UNSIGNED ) AS breeders,
                    CAST( IFNULL( SUM( pairs ), 0 ) AS UNSIGNED) AS pairs, 
                    CAST( IFNULL( COUNT( DISTINCT breedId ), 0 ) AS UNSIGNED) AS breeds, CAST( IFNULL( COUNT( DISTINCT colorId ), 0 ) AS UNSIGNED) AS colors, 		
                    CAST( IFNULL( SUM( layDames), 0 ) AS UNSIGNED) AS layDames, IFNULL( AVG( layEggs ), 0 ) AS layEggs, IFNULL( AVG( layWeight ), 0 ) AS layWeight,
                    CAST( IFNULL( SUM( broodEggs ), 0 ) AS UNSIGNED) AS broodEggs, CAST( IFNULL( SUM( broodFertile ), 0 ) AS UNSIGNED) AS broodFertile, CAST( IFNULL( SUM( broodHatched ), 0 ) AS UNSIGNED) AS broodHatched,
                    CAST( IFNULL( SUM( showCount ), 0 ) AS UNSIGNED) AS showCount, IFNULL( AVG( showScore), 0 ) AS showScore	
               
                FROM districts
                
                # add results per district
                LEFT JOIN result ON result.districtId = districts.id 
                    AND result.year = :year # the year to get data for
                    AND result.breedId = :breedId		
                GROUP BY districts.parentId
                ORDER BY districts.name
            ) AS results
            
            # add users per LV district or its subs
            LEFT JOIN (
                WITH RECURSIVE districts( parentId, id, latitude, longitude, name ) AS (
                    SELECT id, id, latitude, longitude, name FROM district WHERE parentId=1 # all bdrg children
                    UNION
                    SELECT districts.parentId, district.id, null, null, null # add child, no details needed
                    FROM districts JOIN district ON district.parentId=districts.id
                )
                SELECT districts.id AS districtId, CAST( COUNT( DISTINCT user.id ) AS UNSIGNED ) AS members
                FROM districts
                LEFT JOIN user 
                    ON YEAR( user.start ) <= :year AND ( user.`end` IS NULL OR YEAR( user.end ) >= :year )
                    AND user.clubId = districts.id
                    
                GROUP BY districts.parentId
            
            ) AS members ON members.districtId = results.id
        ' );
        return Query::selectArray( $stmt, $args );
    }
    public static function districtsForSection( $year, $sectionId ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( '           
            SELECT results.*, members.members
            
            FROM (
                # start with all distrcts
                WITH RECURSIVE districts( parentId, id, latitude, longitude, name ) AS (
                    SELECT id, id, latitude, longitude, name FROM district WHERE parentId=1 # all bdrg children
                    UNION
                    SELECT districts.parentId, district.id, null, null, null # add child, no details needed
                    FROM districts JOIN district ON district.parentId=districts.id
                )
            
                # get all data from results per district
                SELECT 
                    `year`,
                    districts.id, districts.latitude, districts.longitude, districts.name,
                    CAST( IFNULL( SUM( breeders ), 0 ) AS UNSIGNED ) AS breeders,
                    CAST( IFNULL( SUM( pairs ), 0 ) AS UNSIGNED) AS pairs, 
                    CAST( IFNULL( COUNT( DISTINCT breedId ), 0 ) AS UNSIGNED) AS breeds, CAST( IFNULL( COUNT( DISTINCT colorId ), 0 ) AS UNSIGNED) AS colors, 		
                    CAST( IFNULL( SUM( layDames), 0 ) AS UNSIGNED) AS layDames, IFNULL( AVG( layEggs ), 0 ) AS layEggs, IFNULL( AVG( layWeight ), 0 ) AS layWeight,
                    CAST( IFNULL( SUM( broodEggs ), 0 ) AS UNSIGNED) AS broodEggs, CAST( IFNULL( SUM( broodFertile ), 0 ) AS UNSIGNED) AS broodFertile, CAST( IFNULL( SUM( broodHatched ), 0 ) AS UNSIGNED) AS broodHatched,
                    CAST( IFNULL( SUM( showCount ), 0 ) AS UNSIGNED) AS showCount, IFNULL( AVG( showScore), 0 ) AS showScore	
               
                FROM districts
                
                # add results per district
                LEFT JOIN result ON result.districtId = districts.id 
                    AND result.year = :year # the year to get data for
                    AND result.breedId IN ( # check if in section 
                        WITH RECURSIVE sections( parentId, id ) AS (
                            SELECT id, id FROM section WHERE id = :sectionId # the root sectionId
                            UNION
                            SELECT sections.id, section.id
                            FROM sections JOIN section ON section.parentId=sections.id
                        )
                        SELECT Breed.id FROM Breed, sections
                        WHERE Breed.sectionId = sections.id	
                    )
                    
                GROUP BY districts.parentId
                ORDER BY districts.name
            ) AS results
            
            # add users per LV district or its subs
            LEFT JOIN (
                WITH RECURSIVE districts( parentId, id, latitude, longitude, name ) AS (
                    SELECT id, id, latitude, longitude, name FROM district WHERE parentId=1 # all bdrg children
                    UNION
                    SELECT districts.parentId, district.id, null, null, null # add child, no details needed
                    FROM districts JOIN district ON district.parentId=districts.id
                )
                SELECT districts.id AS districtId, CAST( COUNT( DISTINCT user.id ) AS UNSIGNED ) AS members
                FROM districts
                LEFT JOIN user 
                    ON YEAR( user.start ) <= :year AND ( user.`end` IS NULL OR YEAR( user.end ) >= :year )
                    AND user.clubId = districts.id
                    
                GROUP BY districts.parentId
            
            ) AS members ON members.districtId = results.id

        ' );
        return Query::selectArray( $stmt, $args );
    }


    public static function yearsForColor( $districtId, $colorId ) {
        $args = get_defined_vars();
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
        return Query::selectArray( $stmt, $args );
    }
    public static function yearsForBreed( $districtId, $breedId ) {
        $args = get_defined_vars();
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
        return Query::selectArray( $stmt, $args );
    }
    public static function yearsForSection( $districtId, $sectionId ) {
        $args = get_defined_vars();
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
        return Query::selectArray( $stmt, $args );
    }

}