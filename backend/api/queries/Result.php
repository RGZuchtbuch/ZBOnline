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
        int $districtId, int $year, string $group, ? int $breederId, ? string $name,
        int $sectionId, int $breedId, ? int $colorId,
        ? int $layDames, ? float $layEggs, ? float $layWeight,
        ? int $broodEggs, ? int $broodFertile, ? int $broodHatched,
        ? int $showCount, ? float $showScore
    ) : ? int {
        if( $breederId == null ) $breederId = 0;
        if( $name == null ) $name = '';
        $args = get_defined_vars(); // all vars in scope
        /*
        $stmt = Query::prepare( '
            INSERT INTO result ( 
                districtId, year, `group`, breederId, name ,
                sectionId, breedId, colorId, 
                layDames, layEggs, layWeight,
                broodEggs, broodFertile, broodHatched,
                showCount, showScore
            )
            VALUES ( 
                :districtId, :year, :group, :breederId, :name, 
                :sectionId, :breedId, :colorId,
                :layDames, :layEggs, :layWeight,
                :broodEggs, :broodFertile, :broodHatched,
                :showCount, :showScore
            )
        ');
        */
        $stmt = Query::prepare( '
            REPLACE INTO result ( 
                districtId, year, `group`, breederId, name ,
                sectionId, breedId, colorId, 
                layDames, layEggs, layWeight,
                broodEggs, broodFertile, broodHatched,
                showCount, showScore
            )
            VALUES ( 
                :districtId, :year, :group, :breederId, :name, 
                :sectionId, :breedId, :colorId,
                :layDames, :layEggs, :layWeight,
                :broodEggs, :broodFertile, :broodHatched,
                :showCount, :showScore
            )
        ');
        return Query::insert( $stmt, $args );
    }
}