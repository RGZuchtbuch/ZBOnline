<?php

namespace App\queries\map\breed;

use App\queries\Query;
use http\Exception\BadMessageException;

//                CAST( IFNULL( SUM( broodFertile ), 0 ) AS UNSIGNED) AS broodFertile,
//
//                CAST( IFNULL( SUM( broodHatched ), 0 ) AS UNSIGNED) AS broodHatched,

//                CAST( SUM(IFNULL(  broodFertile,0 ) ) / NULLIF( SUM( IFNULL( broodEggs, 0 ) ), 0 ) AS UNSIGNED ) AS broodFertile,
//                CAST( SUM( IFNULL( broodHatched, 0 ) ) / NULLIF( SUM( IFNULL( broodEggs, 0 ) ), 0 ) AS UNSIGNED ) AS broodHatched,
//
class Select extends Query
{
    public static function execute( ...$args ) : ? array {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '           
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
        return static::selectArray( $stmt, $args );
    }

    private static function validate( int $year, int $breedId ) : array {
        if( $year>1900 && $breedId>0 ) {
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