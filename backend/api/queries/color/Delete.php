<?php

namespace App\queries\color;

use App\queries\Query;

class Delete
{
    public static function execute ( int $colorId ) : bool {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            DELETE FROM color WHERE id=:colorId
        ' );
        return Query::delete( $stmt, $args );
    }
}