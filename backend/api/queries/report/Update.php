<?php

namespace App\queries\report;

use App\queries\Query;

class Update
{
    public static function execute (
        int $id, int $breederId, int $districtId, int $year, string $group, int $sectionId, int $breedId, ? int $colorId, ? string $name, ? string $paired, ? string $notes
    ) : bool {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            UPDATE report 
            SET breederId=:breederId, 
                districtId=:districtId, year=:year, group=:group, 
                sectionId=:sectionId, breedId=:breedId, colorId=:colorId, 
                name=:name, paired=:paired, 
                notes=:notes
            WHERE id=:id   
        ' );
        return Query::update( $stmt, $args );
    }
}