<?php

namespace App\Queries;

use PDO;
use PDOStatement;
use App\routes\Controller;

class Result
{

    public static function get( int $id ) : ? array {
        $args = [ 'id'=>$id ];
        $stmt = Query::prepare( '
            SELECT * FROM result WHERE id=:id
        ' );
        $pair = Query::select( $stmt, $args );
        return $pair;
    }

    public static function insert(
        ? int $reportId, int $districtId, int $year, string $group,
        int $breeders, int $pairs,
        int $sectionId, int $breedId, ? int $colorId,
        ? int $layDames, ? float $layEggs, ? float $layWeight,
        ? int $broodEggs, ? int $broodFertile, ? int $broodHatched,
        ? int $showCount, ? float $showScore
    ) : bool {
 //       if( $breederId == null ) $breederId = 0;
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            INSERT INTO result ( 
                reportId, districtId, `year`, `group`,
                breeders, pairs,
                sectionId, breedId, colorId, 
                layDames, layEggs, layWeight,
                broodEggs, broodFertile, broodHatched,
                showCount, showScore
            )
            VALUES ( 
                :reportId, :districtId, :year, :group,
                :breeders, :pairs,
                :sectionId, :breedId, :colorId,
                :layDames, :layEggs, :layWeight,
                :broodEggs, :broodFertile, :broodHatched,
                :showCount, :showScore
            )
        ');
        return Query::insert( $stmt, $args );
    }

    public static function replace (
        int $id,
        ? int $reportId, int $districtId, int $year, string $group,
        int $breeders, int $pairs,
        int $sectionId, int $breedId, ? int $colorId,
        ? int $layDames, ? float $layEggs, ? float $layWeight,
        ? int $broodEggs, ? int $broodFertile, ? int $broodHatched,
        ? int $showCount, ? float $showScore
    ) : bool {
//        if( $breederId == null ) $breederId = 0;
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            REPLACE INTO result ( 
                id, 
                reportId, districtId, `year`, `group`,
                breeders, pairs,
                sectionId, breedId, colorId, 
                layDames, layEggs, layWeight,
                broodEggs, broodFertile, broodHatched,
                showCount, showScore
            )
            VALUES ( 
                :id,
                :reportId, :districtId, :year, :group,
                :breeders, :pairs,
                :sectionId, :breedId, :colorId,
                :layDames, :layEggs, :layWeight,
                :broodEggs, :broodFertile, :broodHatched,
                :showCount, :showScore
            )
        ');
        return Query::replace( $stmt, $args );
    }

    public static function delete( int $id ) : bool {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( "
            DELETE FROM result WHERE id=:id
        ");
        return Query::delete( $stmt, $args );
    }

    public static function deleteForReport( int $reportId ) : bool {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( "
            DELETE FROM result WHERE reportId=:reportId
        ");
        return Query::delete( $stmt, $args );
    }
}