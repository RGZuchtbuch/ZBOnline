<?php

namespace App\queries\map\section;

use App\queries\Query;
use http\Exception\BadMessageException;

class Select extends Query
{
    public static function execute( ...$args ) : ? array {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '           
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
                    CAST( IFNULL( SUM( layDames), 0 ) AS UNSIGNED) AS layDames, CAST( IFNULL( AVG( layEggs ), 0 ) AS UNSIGNED) AS layEggs, CAST( IFNULL( AVG( layWeight ), 0 ) AS UNSIGNED) AS layWeight,
                    CAST( IFNULL( SUM( broodEggs ), 0 ) AS UNSIGNED) AS broodEggs, CAST( IFNULL( SUM( broodFertile ), 0 ) AS UNSIGNED) AS broodFertile, CAST( IFNULL( SUM( broodHatched ), 0 ) AS UNSIGNED) AS broodHatched,
                    CAST( IFNULL( SUM( showCount ), 0 ) AS UNSIGNED) AS showCount, CAST( IFNULL( AVG( showScore), 0 ) AS UNSIGNED) AS showScore	
               
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
                        SELECT breed.id FROM breed, sections
                        WHERE breed.sectionId = sections.id	
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
        return static::selectArray( $stmt, $args );
    }

    private static function validate( int $year, int $sectionId ) : array {
        if( $year>1900 && $sectionId>0 ) {
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}