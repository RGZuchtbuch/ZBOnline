<?php

namespace App\queries\report;

use App\queries\Query;

class Insert
{
    public static function execute (
        int $breederId, int $districtId, int $year, string $group, int $sectionId, int $breedId, ? int $colorId, ? string $name, ? string $paired, ? string $notes
    ) : bool {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            INSERT INTO report ( breederId, districtId, `year`, `group`, sectionId, breedId, colorId, `name`, paired, notes )
            VALUES ( :breederId, :districtId, :year, :group, :sectionId, :breedId, :colorId, :name, :paired, :notes )
        ' );
        return Query::insert( $stmt, $args );
    }
}