<?php

namespace App\Model;

use App\Config;

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
                root.id, root.name, root.latitude, root.longitude,
                
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
            FROM district AS root
            LEFT JOIN (
                SELECT  result.*, district.rootId FROM result 
                LEFT JOIN district ON district.id=result.districtId
                WHERE `year`=:year AND colorId=:colorId               
            ) AS results ON results.rootId=root.id
            WHERE root.id = root.rootId            
            GROUP BY root.id
        " );
/*
        $stmt = Query::prepare( " 
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
        " );
*/
        return Query::selectArray( $stmt, $args );
    }
    public static function districtsForBreed( $year, $breedId ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( " 
            SELECT 
                COUNT(*) AS `count`, results.colorId, 
                root.id, root.name, root.latitude, root.longitude,
                
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
            FROM district AS root
            LEFT JOIN (
                SELECT  result.*, district.rootId FROM result 
                LEFT JOIN district ON district.id=result.districtId
                WHERE `year`=:year AND breedId=:breedId               
            ) AS results ON results.rootId=root.id
            WHERE root.id = root.rootId            
            GROUP BY root.id
        " );
/*
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
*/
        return Query::selectArray( $stmt, $args );
    }
    public static function districtsForSection( $year, $sectionId ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( "
            SELECT 
                COUNT(*) AS `count`, results.colorId, 
                root.id, root.name, root.latitude, root.longitude,
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
                 
            FROM district AS root
            LEFT JOIN (
                SELECT  result.*, district.rootId FROM result 
                LEFT JOIN district ON district.id=result.districtId
                LEFT JOIN breed ON breed.id = result.breedId
                WHERE `year`=:year
                    AND breed.sectionId IN (
                        SELECT id
                        FROM (
                            SELECT @root:=:sectionId, @parents:=@root
                        ) AS init, (
                            SELECT id, parentId FROM section ORDER BY parentId, id
                        ) AS sorted
                        WHERE ( FIND_IN_SET( parentId, @parents )>0 AND @parents:=CONCAT( @parents, ',', id ) ) OR id=@root
                    ) 
            ) AS results ON results.rootId=root.id
            WHERE root.id = root.rootId
            GROUP BY root.id        
        " );
/*

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
*/
        return Query::selectArray( $stmt, $args );
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
	                        SELECT @districtRoot:=:districtId, @districts:=@districtRoot
	                    ) AS init, (
                            SELECT id, parentId FROM district 
                            ORDER BY parentId, id
	                    ) AS sorted
						WHERE (FIND_IN_SET(parentId, @districts) > 0 AND @districts:=CONCAT(@districts, ',', id)) OR id = @districtRoot 
	                )
	                AND breed.sectionId IN (
						SELECT id FROM (
							SELECT @sectionRoot:=:sectionId, @sections:=@sectionRoot
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