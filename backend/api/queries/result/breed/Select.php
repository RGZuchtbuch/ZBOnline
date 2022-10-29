<?php

namespace App\queries\result\breed;

use App\queries\Query;

// selects breed's result
class Select
{
    public static function execute( int $breedId, int $districtId, int $year, string $group ) : array {
        $args = get_defined_vars(); // all vars in scope
        $args['aDistrictId'] = $districtId;
        $args['aYear'] = $year;
        $args['aGroup'] = $group;
        $stmt = Query::prepare( '
            SELECT result.id id, "*" AS name,
                   result.id AS resultId, reportId, :aDistrictId AS districtId, breeders, pairs, :aYear AS `year`, :aGroup AS `group`,
                   breed.sectionId, breed.id AS breedId, null AS colorId, 
                   result.layDames, layEggs, layWeight,
                   result.broodEggs, broodFertile, broodHatched,
                   result.showCount, showScore
            FROM breed
            LEFT JOIN result 
                ON result.breedId = breed.id AND result.colorId IS NULL
                AND result.districtId=:districtId 
                AND result.year=:year
                AND result.group=:group
            WHERE breed.id=:breedId
        ' );
        return Query::selectArray( $stmt, $args );
    }
}