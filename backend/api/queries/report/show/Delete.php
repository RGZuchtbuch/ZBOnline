<?php

namespace App\queries\report\show;

use App\queries\Query;

class Delete
{
    public static function execute ( int $reportId ) : bool {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            DELETE FROM report_show WHERE reportId=:reportId
        ' );
        return Query::delete( $stmt, $args );
    }
}