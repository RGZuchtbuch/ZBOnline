<?php

namespace App\queries\result\breed\colors;

use App\queries\Query;

// selects breeds results for each color
class Select
{
    public static function execute( int $breedId, int $districtId, int $year, string $group ) : array {
        $args = get_defined_vars(); // all vars in scope
        $args['aDistrictId'] = $districtId;
        $args['aYear'] = $year;
        $args['aGroup'] = $group;
        $stmt = Query::prepare( '
            SELECT result.id id, color.name,
                   result.id AS resultId, reportId, :aDistrictId AS districtId, breeders, pairs, :aYear AS `year`, :aGroup AS `group`,
                   breed.sectionId, color.breedId, color.id AS colorId, 
                   result.layDames, layEggs, layWeight,
                   result.broodEggs, broodFertile, broodHatched,
                   result.showCount, showScore
            FROM color
            LEFT JOIN breed ON breed.id = color.breedId
            LEFT JOIN result 
                ON result.colorId = color.id
                AND result.districtId=:districtId 
                AND result.year=:year
                AND result.group=:group
            WHERE color.breedId=:breedId
            ORDER BY color.name 
        ' );
        return Query::selectArray( $stmt, $args );
    }
}