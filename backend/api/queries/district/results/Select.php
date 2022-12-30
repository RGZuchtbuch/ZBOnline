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
            SELECT districtId, section.id AS sectionId, section.name AS sectionName, breed.id AS breedId, breed.name AS breedName, color.id AS colorId, color.name AS colorName, 
                SUM(breeders) AS breeders, SUM(pairs) AS pairs, 
                SUM(LayEggs) AS layEggs, AVG(layWeight) AS layWeight,
                SUM(broodEggs) AS broodEggs, SUM(broodFertile) AS broodFertile, SUM(broodHatched) AS broodHatched,
                SUM(showCount) AS showCount, AVG(showScore) AS showScore
            FROM result
                LEFT JOIN section ON section.id = result.sectionId
                LEFT JOIN breed ON breed.id = result.breedId
                LEFT JOIN color ON color.id = result.colorId
            GROUP BY districtId, breed.id, color.id
            HAVING districtId=:distcitId
            ORDER BY sectionId, breedName, colorId
        ' );
        return static::select( $stmt, $args );
    }

    private static function validate( int $districtId ) : array {
        if( $districtId>0 ) {
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}