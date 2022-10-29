<?php

namespace App\queries\color;

use App\queries\Query;

class Insert
{
    public static function execute (
        int $breedId, string $name, string $info
    ) : bool {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            INSERT INTO color ( breedId, name, info )
            VALUES ( :breedId, :name, :info )
        ' );
        return Query::insert( $stmt, $args );
    }
}