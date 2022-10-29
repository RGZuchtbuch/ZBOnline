<?php

namespace App\queries\color;

use App\queries\Query;

class Update
{
    public static function execute (
        int $colorId, int $breedId, string $name, string $info
    ) : bool {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            UPDATE color
            SET breedId=:breedId, name=:name, info=:info
            WHERE id=:colorId
        ' );
        return Query::update( $stmt, $args );
    }
}