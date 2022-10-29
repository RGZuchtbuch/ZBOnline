<?php

namespace App\queries\breed;

use App\queries\Query;

class Select
{
    public static function execute( int $breedId ) : ? array {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            SELECT *
            FROM breed
            WHERE id=:breedId
        ' );
        return Query::select( $stmt, $args );
    }
}