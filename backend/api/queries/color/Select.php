<?php

namespace App\queries\color;

use App\queries\Query;

class Select
{
    public static function execute( int $colorId ) : ? array {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            SELECT *
            FROM color
            WHERE id=:colorId
        ' );
        return Query::select( $stmt, $args );
    }
}