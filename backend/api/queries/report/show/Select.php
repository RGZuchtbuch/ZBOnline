<?php

namespace App\queries\report\show;

use App\queries\Query;

class Select
{
    public static function execute( int $reportId ) : ? array { // not the reportId ( not id )
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            SELECT * FROM report_show WHERE reportId=:reportId
        ' );
        return Query::select( $stmt, $args );
    }
}