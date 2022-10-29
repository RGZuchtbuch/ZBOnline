<?php

namespace App\queries\breed;

use App\queries\Query;

class Update
{
    public static function execute (
        int $id, int $name, int $sectionId, int $subsectionId, int $broodGroup, string $image, int $eggs, int $eggWeight, int $sireRing, int $dameRing, int $sireWeight, int $dameWeight, string $info
    ) : bool {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            UPDATE breed 
            SET name=:name, 
                sectionId=:sectionId, subsectionId=:subsectionId, broodgroup=:broodGroup, image=:image, 
                eggs=:eggs, eggweight=:eggWeight, 
                sirering=:sireRing, damering=:dameRing, 
                sireweight=:sireWeight, dameweight=:dameWeight, 
                info=:info
            WHERE id=:id   
        ' );
        return Query::update( $stmt, $args );
    }
}