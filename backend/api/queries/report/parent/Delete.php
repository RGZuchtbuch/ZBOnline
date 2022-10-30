<?php

namespace App\queries\report\parents;

use App\queries\Query;

class Delete
{
    public static function execute ( int $id ) : bool {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            DELETE FROM report_parent WHERE id=:id
        ' );
        return Query::delete( $stmt, $args );
    }
}