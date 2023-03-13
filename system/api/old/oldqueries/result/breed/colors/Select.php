<?php

namespace App\queries\result\breed\colors;

use App\queries\BaseModel;

// selects breeds results for each color
class Select
{
    public static function execute( int $breedId, int $districtId, int $year, string $group ) : array {
        $args = get_defined_vars(); // all vars in scope
        $args['aDistrictId'] = $districtId;
        $args['aYear'] = $year;
        $args['aGroup'] = $group;
        $stmt = BaseModel::prepare( '
            SELECT result.id id, color.name,
                   result.id AS resultId, reportId, :aDistrictId AS districtId, breeders, pairs, :aYear AS `year`, :aGroup AS `group`,
                   Breed.sectionId, color.breedId, color.id AS colorId, 
                   result.layDames, layEggs, layWeight,
                   result.broodEggs, broodFertile, broodHatched,
                   result.showCount, showScore
            FROM color
            LEFT JOIN Breed ON Breed.id = color.breedId
            LEFT JOIN result 
                ON result.colorId = color.id
                AND result.districtId=:districtId 
                AND result.year=:year
                AND result.group=:group
            WHERE color.breedId=:breedId
            ORDER BY color.name 
        ' );
        return BaseModel::selectArray( $stmt, $args );
    }
}