<?php

namespace App\queries\district\results;

use App\queries\Query;
use App\routes\Controller;
use http\Exception\BadMessageException;

class Select extends Query
{
    public static function execute( ...$args ) : ? array {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            SELECT result.id, districtId, year, section.id AS sectionId, section.name AS sectionName, breed.id AS breedId, breed.name AS breedName, color.id AS colorId, color.name AS colorName, 
                SUM(breeders) AS breeders, SUM(pairs) AS pairs, 
                SUM(layDames) AS layDames, AVG(LayEggs) AS layEggs, AVG(layWeight) AS layWeight,
                SUM(broodEggs) AS broodEggs, SUM(broodFertile) AS broodFertile, SUM(broodHatched) AS broodHatched,
                SUM(showCount) AS showCount, AVG(showScore) AS showScore
            FROM result
                LEFT JOIN section ON section.id = result.sectionId
                LEFT JOIN breed ON breed.id = result.breedId
                LEFT JOIN color ON color.id = result.colorId
            WHERE districtId=:districtId AND year=:year
            GROUP BY districtId, section.id, breed.name, color.name
            ORDER BY section.id, breed.name, color.name
        ' );
        return static::selectArray( $stmt, $args );
    }

    private static function validate( int $districtId , int $year) : array {
        if( $districtId>0 && $year > 1900 && $year <= date("Y") ) {
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}