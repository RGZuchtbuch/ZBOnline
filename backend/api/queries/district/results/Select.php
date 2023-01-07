<?php

namespace App\queries\district\results;

use App\queries\Query;
use App\routes\Controller;
use Exception;
use http\Exception\BadMessageException;
use http\Exception\BadUrlException;
use PDOException;

class Select extends Query
{
    public static function execute( ...$args ) : ? array {
        $args = static::validate( ...$args );
        try {
            $stmt = static::prepare('
                WITH RECURSIVE districts( id, childId, name ) AS 
                (
                    SELECT id, id AS childId, name FROM district WHERE id=:districtId
                    UNION
                    SELECT districts.id AS id, district.id AS childId, districts.name AS name
                    FROM districts JOIN district ON district.parentId=districts.childId
                )
                
                SELECT districts.id AS districtId, sections.id AS sectionId, sections.name AS sectionName, 
                    results.id, results.breedId, results.breedName, results.colorId, results.colorName,
                    CAST( SUM( results.breeders ) AS UNSIGNED ) AS breeders, CAST( SUM( results.pairs ) AS UNSIGNED ) AS pairs,
                    CAST( SUM( results.layDames ) AS UNSIGNED ) AS layDames, AVG( results.layEggs ) AS layEggs, AVG( results.layWeight ) AS layWeight,
                    CAST( SUM( results.broodEggs ) AS UNSIGNED ) AS broodEggs, CAST( SUM( results.broodFertile ) AS UNSIGNED ) AS broodFertile, CAST( SUM( results.broodHatched ) AS UNSIGNED ) AS broodHatched, 
                    CAST( SUM( results.showCount ) AS UNSIGNED ) AS showCount, AVG( results.showScore ) AS showScore
                FROM districts
                
                JOIN (
                    WITH RECURSIVE sections( id, childId, NAME, `order` ) AS 
                    (
                        SELECT id, id AS childId, NAME, `order` FROM section WHERE id IN ( 3, 11, 12, 13, 5, 6 )
                         UNION
                        SELECT sections.id AS id, section.id AS childId, sections.name AS name, sections.order AS `order`
                        FROM sections JOIN section ON section.parentId=sections.childId
                    )
                    SELECT * FROM sections
                ) AS sections
                
                LEFT JOIN 
                (
                    SELECT result.*, breed.sectionId AS sectionId, breed.name AS breedName, color.name AS colorName FROM result 
                    LEFT JOIN breed ON breed.id=result.breedId
                    LEFT JOIN color ON color.id=result.colorId
                    WHERE result.year=:year
                ) AS results ON  results.districtId=districts.childId AND results.sectionId=sections.childId
                WHERE results.breedId IS NOT NULL
                GROUP BY sections.id, results.breedId, results.colorId
                ORDER BY sections.order, results.breedName, results.colorName
            ');
            return static::selectArray($stmt, $args);
        } catch( PDOException $e ) {
            throw new PDOException( $e->getMessage() );
        }
    }

    private static function validate( int $districtId , int $year) : array {
        if( $districtId>0 && $year > 1900 && $year <= date("Y") ) {
            return get_defined_vars();
        };
        throw new BadUrlException( "Error in query args");
    }
}