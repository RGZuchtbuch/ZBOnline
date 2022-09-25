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
        int $year, int $breederId, int $districtId, int $group,
        int $sectionId, int $breedId, ? int $colorId,
        int $lay_dames, int $lay_eggs, int $lay_weight,
        int $brood_eggs, int $brood_fertile, int $brood_hatched,
        int $show_count, int $show_score
    ) : ? int {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            INSERT INTO result ( 
                year, breederId, districtId, `group`, 
                sectionId, breedId, colorId, 
                lay_dames, lay_eggs, lay_weight,
                brood_eggs, brood_fertile, brood_hatched,
                show_count, show_score
            )
            VALUES ( 
                :year, :breederId, :name, :group, 
                :sectionId, :breedId, :colorId,
                :lay_dames, :lay_eggs, :lay_weight,
                :brood_eggs, :brood_fertile, :brood_hatched,
                :show_count, :show_score
            )
        ');
        return Query::insert( $stmt, $args );
    }
}