<?php

namespace App\queries\result;

use App\controllers\Controller;
use App\queries\Query;
use http\Exception\BadMessageException;

class Insert extends Query
{
    public static function execute (...$args ) : bool {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            INSERT INTO result (
                reportId, districtId, `year`, `group`,
                sectionId, breedId, colorId, 
                breeders, pairs,
                layDames, layEggs, layWeight,
                broodEggs, broodFertile, broodHatched,
                showCount, showScore, modifier
            )
            VALUES ( 
                :reportId, :districtId, :year, :group,
                :sectionId, :breedId, :colorId,
                :breeders, :pairs,                        
                :layDames, :layEggs, :layWeight,
                :broodEggs, :broodFertile, :broodHatched,
                :showCount, :showScore, :modifier
            )
        ' );
        return static::insert( $stmt, $args );
    }

    private static function validate(
        ? int $reportId, int $districtId, int $year, string $group,
        int $sectionId, int $breedId, ? int $colorId,
        ? int $breeders, ? int $pairs,
        ? int $layDames, ? float $layEggs, ? float $layWeight,
        ? int $broodEggs, ? int $broodFertile, ? int $broodHatched,
        ? int $showCount, ? float $showScore
    ) : array {
        if(
            ( $reportId===null || $reportId>0 ) && $districtId>0 &&
            ( $year>1900 && $year<9999 ) &&
            ( $group==="I" || $group==="II" || $group==="III" ) &&
            $sectionId>0 && $breedId>0 && (( $sectionId===5 && $colorId===null ) || $colorId>0 ) &&
            ( $breeders===null || ( $breeders>0 && $breeders<1000000 ) ) &&
            ( $pairs===null || ($pairs>0 && $pairs<1000000 ) ) &&
            ( $layDames===null || ( $layDames>0 && $layDames<1000000000 ) ) &&
            ( $layEggs===null || ($layEggs>=0 && $layEggs<10000 ) ) &&
            ( $layWeight===null || ($layWeight>0 && $layWeight<10000 ) ) &&
            ( $broodEggs===null || ($broodEggs>0 && $broodEggs<1000000000 ) ) &&
            ( $broodFertile===null || ( $broodFertile>=0 && $broodFertile<=$broodEggs ) ) &&
            ( $broodHatched===null || ( ( $broodFertile && $broodHatched<=$broodFertile ) || ( !$broodFertile && $broodHatched<=$broodEggs ) || ( $sectionId===5 && $broodHatched>=0 ) ) ) &&
            ( $showCount===null || ( $showCount>0 && $showCount<1000000000 ) ) &&
            ( $showScore===null || ( $showScore>=89 && $showScore<=97 ) )
    ) {
            $modifier = Controller::$requester['id']; // add to def vars
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}