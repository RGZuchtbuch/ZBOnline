<?php

namespace App\queries\result\breed;

use App\queries\BaseModel;

// selects Breed's result
class Select
{
    public static function execute( int $breedId, int $districtId, int $year, string $group ) : array {
        $args = get_defined_vars(); // all vars in scope
        $args['aDistrictId'] = $districtId;
        $args['aYear'] = $year;
        $args['aGroup'] = $group;
        $stmt = BaseModel::prepare( '
            SELECT result.id id, "*" AS name,
                   result.id AS resultId, reportId, :aDistrictId AS districtId, breeders, pairs, :aYear AS `year`, :aGroup AS `group`,
                   Breed.sectionId, Breed.id AS breedId, null AS colorId, 
                   result.layDames, layEggs, layWeight,
                   result.broodEggs, broodFertile, broodHatched,
                   result.showCount, showScore
            FROM Breed
            LEFT JOIN result 
                ON result.breedId = Breed.id AND result.colorId IS NULL
                AND result.districtId=:districtId 
                AND result.year=:year
                AND result.group=:group
            WHERE Breed.id=:breedId
        ' );
        return BaseModel::selectArray( $stmt, $args );
    }
}