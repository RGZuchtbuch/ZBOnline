<?php

namespace App\queries\district\breeders;

use App\queries\Query;

class Select
{
    public static function execute( int $districtId ) : ? array {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            SELECT * FROM user WHERE districtId=:districtId
        ' );
        return Query::selectArray( $stmt, $args );
    }
}