<?php

namespace App\queries\result\breeds;

use App\queries\Query;

class Select
{
    public static function execute( int $districtId, int $sectionId, int $year, string $group ) : array {
        $args = get_defined_vars(); // all vars in scope
        $args['aDistrictId'] = $districtId; // to ensure valid district if result not exists
        $args['aYear'] = $year;
        $args['aGroup'] = $group;
        $stmt = Query::prepare( '
            SELECT breed.id, breed.name, 
                   result.id AS resultId, reportId, :aDistrictId AS districtId, breeders, pairs, :aYear AS `year`, :aGroup AS `group`,
                   result.sectionId, breedId, colorId, 
                   result.layDames, layEggs, layWeight,
                   result.broodEggs, broodFertile, broodHatched,
                   result.showCount, showScore
            FROM breed
            LEFT JOIN result 
                ON result.breedId=breed.id AND result.colorId IS NULL
                AND result.districtId=:districtId 
                AND result.year=:year
                AND result.group=:group
            WHERE breed.sectionId=:sectionId
            ORDER BY breed.name 
        ' ); // TODO problem with breed.id vs result.id
        return Query::selectArray( $stmt, $args );
    }
}