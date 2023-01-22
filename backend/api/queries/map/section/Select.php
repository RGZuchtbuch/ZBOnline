<?php

namespace App\queries\map\section;

use App\queries\Query;
use http\Exception\BadMessageException;

class Select extends Query
{
    public static function execute( ...$args ) : ? array {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '           
            SELECT districts.id, districts.latitude AS latitude, districts.longitude AS longitude, districts.name AS name, 
                COUNT( results.id ) AS results, COUNT( DISTINCT results.breedId) AS breeds, 					 
				CAST( IFNULL( SUM( results.breeders ), 0 ) AS UNSIGNED ) AS breeders, CAST( IFNULL( SUM(results.pairs ), 0 ) AS UNSIGNED) AS pairs, 
                CAST( IFNULL( SUM(results.layDames), 0 ) AS UNSIGNED) AS layDames, IFNULL( AVG(results.layEggs ), 0 ) AS layEggs, IFNULL( AVG( results.layWeight ), 0 ) AS layWeight,
                CAST( IFNULL( SUM( broodEggs ), 0 ) AS UNSIGNED) AS broodEggs, CAST( AVG( broodFertile / broodEggs ) AS FLOAT ) AS broodFertile, CAST( AVG( broodHatched / broodEggs ) AS FLOAT ) AS broodHatched,
                CAST( IFNULL( SUM(results.showCount ), 0 ) AS UNSIGNED) AS showCount, IFNULL( AVG(showScore), 0 ) AS showScore	
                        
            FROM (
                WITH RECURSIVE districts( id, childId, latitude, longitude, name ) AS (
                    SELECT id, id AS childId, latitude, longitude, name FROM district WHERE parentId=1
                    UNION
                    SELECT districts.id, district.id AS childId, districts.latitude, districts.longitude, districts.name
                    FROM districts JOIN district ON district.parentId=districts.childId
                )
                SELECT * FROM districts
            ) AS districts
            
            LEFT JOIN (
                SELECT result.*, breed.sectionId
                FROM result 
                LEFT JOIN breed ON breed.id=result.breedId
                LEFT JOIN (
                    WITH RECURSIVE sections( id, childId ) AS (
                        SELECT id, id FROM section
                        UNION
                        SELECT sections.id AS id, section.id AS childId FROM sections JOIN section ON section.parentId=sections.childId
                    )
                    SELECT id, childId FROM sections	
                ) AS sections ON sections.childId=breed.sectionId
            
                WHERE result.year=:year
                  
                    AND sections.id=:sectionId
                
            ) AS results ON results.districtId = districts.childId
            
            GROUP BY districts.id, districts.name
            ORDER BY districts.name
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