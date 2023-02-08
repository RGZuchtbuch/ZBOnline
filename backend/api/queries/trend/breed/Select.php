<?php

namespace App\queries\trend\breed;

use App\queries\Query;
use App\routes\Controller;
use http\Exception\BadMessageException;

class Select extends Query
{
    public static function execute( ...$args ) : ? array {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '       
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
        return static::selectArray( $stmt, $args );
    }

    private static function validate( int $districtId, int $breedId ) : array {
        if( $districtId>0 && $breedId>0 ) {
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}

/*
             WITH RECURSIVE districts( id, childId, lattitude, longitude, name ) AS
            (
                SELECT id, id AS childId, lattitude, longitude, name FROM district WHERE parentId=1
                UNION
                SELECT districts.id AS id, district.id AS childId, districts.lattitude AS lattitude, districts.longitude AS longitude, districts.name AS name
                FROM districts JOIN district ON district.parentId=districts.childId
            )

            SELECT districts.id AS districtId, districts.lattitude AS lattitude, districts.longitude AS longitude, districts.name AS name,
                sections.id AS sectionId, COUNT( results.id ) AS results, COUNT( DISTINCT results.breedId) AS breeds,
				CAST( IFNULL( SUM( results.breeders ), 0 ) AS UNSIGNED ) AS breeders, CAST( IFNULL( SUM(results.pairs ), 0 ) AS UNSIGNED) AS pairs,
                CAST( IFNULL( SUM(results.layDames), 0 ) AS UNSIGNED) AS layDames, IFNULL( AVG(results.layEggs ), 0 ) AS layEggs, IFNULL( AVG( results.layWeight ), 0 ) AS layWeight,
                CAST( IFNULL( SUM(results.broodEggs ), 0 ) AS UNSIGNED) AS broodEggs, CAST( IFNULL( SUM(broodFertile ), 0 ) AS UNSIGNED) AS broodFertile, CAST( IFNULL( SUM(broodHatched ), 0 ) AS UNSIGNED) AS broodHatched,
                CAST( IFNULL( SUM(results.showCount ), 0 ) AS UNSIGNED) AS showCount, IFNULL( AVG(showScore), 0 ) AS showScore
            FROM districts

            JOIN
            (
                WITH RECURSIVE sections( id, childId ) AS (
                    SELECT id, id FROM section WHERE id = :sectionId
                    UNION
                    SELECT sections.id AS id, section.id AS childId FROM sections JOIN section ON section.parentId=sections.childId
                )
                SELECT id, childId FROM sections
            ) AS sections

            LEFT JOIN (
                SELECT result.*, breed.sectionId AS sectionId

                FROM result
                LEFT JOIN breed ON breed.id=result.breedId
                WHERE result.year = :year
            ) AS results ON results.districtId=districts.childId AND results.sectionId=sections.childId

            GROUP BY districts.id
            ORDER BY districts.name
 */