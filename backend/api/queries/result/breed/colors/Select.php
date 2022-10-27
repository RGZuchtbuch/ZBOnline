<?php

namespace App\queries\result\breed\colors;

use App\queries\Query;

class Select
{
    public static function execute( int $breedId, int $districtId, int $year, string $group ) : array {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            SELECT color.id AS id, color.name,
                   result.id AS resultId, reportId, districtId, breeders, pairs, `year`, `group`,
                   result.sectionId, result.breedId, colorId, 
                   result.layDames, layEggs, layWeight,
                   result.broodEggs, broodFertile, broodHatched,
                   result.showCount, showScore
            FROM color
            LEFT JOIN result 
                ON result.colorId = color.id
                AND result.districtId=:districtId 
                AND result.year=:year
                AND result.group=:group
            WHERE color.breedId=:breedId
            ORDER BY color.name 
        ' ); // TODO problem with breed.id vs result.id
        return Query::selectArray( $stmt, $args );
    }
}