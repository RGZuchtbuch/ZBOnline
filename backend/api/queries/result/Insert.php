<?php

namespace App\queries\result;

use App\queries\Query;

class Insert
{
    public static function execute (
        ? int $reportId, int $districtId, int $year, string $group,
        int $sectionId, int $breedId, ? int $colorId,
        ? int $breeders, ? int $pairs,
        ? int $layDames, ? float $layEggs, ? float $layWeight,
        ? int $broodEggs, ? int $broodFertile, ? int $broodHatched,
        ? int $showCount, ? float $showScore
    ) : bool {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            INSERT INTO result (
                reportId, districtId, `year`, `group`,
                sectionId, breedId, colorId, 
                breeders, pairs,
                layDames, layEggs, layWeight,
                broodEggs, broodFertile, broodHatched,
                showCount, showScore
            )
            VALUES ( 
                :reportId, :districtId, :year, :group,
                :sectionId, :breedId, :colorId,
                :breeders, :pairs,                        
                :layDames, :layEggs, :layWeight,
                :broodEggs, :broodFertile, :broodHatched,
                :showCount, :showScore
            )
        ' );
        return Query::insert( $stmt, $args );
    }
}