<?php

namespace App\queries\district;

use App\queries\Query;

class Delete
{
    public static function execute ( int $districtId ) : bool {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            DELETE FROM district WHERE id=:districtId
        ' );
        return Query::delete( $stmt, $args );
    }
}