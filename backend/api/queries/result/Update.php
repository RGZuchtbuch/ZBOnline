<?php

namespace App\queries\result;

use App\queries\Query;

class Update
{
    public static function execute (
        ? int $id,
        ? int $reportId, int $districtId, int $year, string $group,
        int $sectionId, int $breedId, ? int $colorId,
        ? int $breeders, ? int $pairs,
        ? int $layDames, ? float $layEggs, ? float $layWeight,
        ? int $broodEggs, ? int $broodFertile, ? int $broodHatched,
        ? int $showCount, ? float $showScore
    ) : bool {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            UPDATE result
            SET reportId=:reportId, districtId=:districtId, `year`=:year, `group`=:group,
                sectionId=:sectionId, breedId=:breedId, colorId=:colorId,
                breeders=:breeders, pairs=:pairs,
                layDames=:layDames, layEggs=:layEggs, layWeight=:layWeight,
                broodEggs=:broodEggs, broodFertile=:broodFertile, broodHatched=:broodHatched,
                showCount=:showCount, showScore=:showScore
            WHERE result.id=:id
        ' ) ;
        return Query::update( $stmt, $args );
    }
}