<?php

namespace App\queries\trend\section;

use App\queries\Query;
use App\routes\Controller;
use http\Exception\BadMessageException;

class Select extends Query
{
    public static function execute( ...$args ) : ? array {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '       
            SELECT 
                years.year, 
                COUNT( results.id ) AS results, COUNT( DISTINCT results.breedId) AS breeds,
                CAST( IFNULL( SUM( breeders ), 0 ) AS UNSIGNED ) AS breeders, CAST( IFNULL( SUM( pairs ), 0 ) AS UNSIGNED) AS pairs,
                CAST( IFNULL( SUM(results.layDames), 0 ) AS UNSIGNED) AS layDames, IFNULL( AVG(results.layEggs ), 0 ) AS layEggs, IFNULL( AVG( results.layWeight ), 0 ) AS layWeight,
                CAST( IFNULL( SUM(results.broodEggs ), 0 ) AS UNSIGNED) AS broodEggs, CAST( IFNULL( SUM(broodFertile ), 0 ) AS UNSIGNED) AS broodFertile, CAST( IFNULL( SUM(broodHatched ), 0 ) AS UNSIGNED) AS broodHatched,
                CAST( IFNULL( SUM(results.showCount ), 0 ) AS UNSIGNED) AS showCount, IFNULL( AVG(showScore), 0 ) AS showScore	
                
            FROM (
                WITH RECURSIVE years AS (
                    SELECT 2000 AS `year` 
                    UNION
                    SELECT year+1 AS `year` FROM years
                    WHERE YEAR < 2023
                )
                SELECT * FROM years
            ) AS years
            
            LEFT JOIN (
                SELECT result.*, breed.sectionId
                FROM result 
                LEFT JOIN breed ON breed.id=result.breedId
                
                WHERE sectionId IN (
                    WITH RECURSIVE sections( id, childId ) AS (
                        SELECT id, id FROM section WHERE id=:sectionId
                        UNION
                        SELECT sections.id AS id, section.id AS childId FROM sections JOIN section ON section.parentId=sections.childId
                    )
                    SELECT childId FROM sections		
                )
                
                AND districtId IN (
                    WITH RECURSIVE districts( id, childId, latitude, longitude, name ) AS (
                        SELECT id, id AS childId, latitude, longitude, name FROM district WHERE id=:districtId
                        UNION
                        SELECT districts.id, district.id AS childId, districts.latitude, districts.longitude, districts.name
                        FROM districts JOIN district ON district.parentId=districts.childId
                    )
                    SELECT childId AS id FROM districts  
                 )             
            ) AS results ON results.year = years.year
            
            GROUP BY years.year
            ORDER BY years.year
        ' );
        return static::selectArray( $stmt, $args );
    }

    private static function validate( int $districtId, int $sectionId ) : array {
        if( $districtId>0 && $sectionId>0 ) {
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