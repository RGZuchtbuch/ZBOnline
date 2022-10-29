<?php

namespace App\queries\district;

use App\queries\Query;

class Select
{
    public static function execute( int $districtId ) : ? array {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            SELECT * FROM district WHERE id=:districtId
        ' );
        return Query::select( $stmt, $args );
    }
}