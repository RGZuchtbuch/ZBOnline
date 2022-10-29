<?php

namespace App\queries\breed;

use App\queries\Query;

class Insert
{
    public static function execute (
        int $name, int $sectionId, int $subsectionId, int $broodGroup, string $image, int $eggs, int $eggWeight, int $sireRing, int $dameRing, int $sireWeight, int $dameWeight, string $info
    ) : bool {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            INSERT INTO breed ( name, sectionId, subsectionId, broodGroup, image, eggs, eggWeight, sireRing, dameRing, sireWeight, dameWeight, info )
            VALUES ( :name, :sectionId, :subsectionId, :broodGroup, :image, :eggs, :eggWeight, :sireRing, :dameRing, :sireWeight, :dameWeight, :info )
        ' );
        return Query::insert( $stmt, $args );
    }
}