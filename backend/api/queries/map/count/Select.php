<?php

namespace App\queries\map\count;

use App\queries\Query;
use App\routes\Controller;
use http\Exception\BadMessageException;

class Select extends Query
{
    public static function execute( ...$args ) : ? array {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
             WITH RECURSIVE districts( id, childId, lattitude, longitude, name ) AS 
            (
                SELECT id, id AS childId, lattitude, longitude, name FROM district WHERE parentId=1
                UNION
                SELECT districts.id AS id, district.id AS childId, districts.lattitude AS lattitude, districts.longitude AS longitude, districts.name AS name
                FROM districts JOIN district ON district.parentId=districts.childId
            )
            
            SELECT districts.id AS districtId, districts.lattitude AS lattitude, districts.longitude AS longitude, districts.name AS name, 
                sections.id AS sectionId, CAST( :year AS UNSIGNED) AS `year`, COUNT( results.id ) AS results, COUNT( DISTINCT results.breedId) AS breeds, CAST( SUM(results.breeders) AS UNSIGNED) AS breeders, CAST( SUM(results.pairs) AS UNSIGNED) AS pairs, 
                CAST( SUM(results.layDames) AS UNSIGNED) AS layDames, AVG(results.layEggs) AS layEggs, AVG(results.layWeight) AS layWeight,
                CAST( SUM(results.broodEggs) AS UNSIGNED) AS broodEggs, CAST( SUM(broodFertile) AS UNSIGNED) AS broodFertile, CAST( SUM(broodHatched) AS UNSIGNED) AS broodHatched,
                CAST( SUM(results.showCount) AS UNSIGNED) AS showCount, AVG(showScore) AS showScore	
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