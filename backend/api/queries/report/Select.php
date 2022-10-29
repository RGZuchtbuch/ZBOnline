<?php

namespace App\queries\report;

use App\queries\Query;

class Select
{
    public static function execute( int $reportId ) : ? array {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            SELECT *
            FROM report
            WHERE id=:reportId
        ' );
        return Query::select( $stmt, $args );
    }
}