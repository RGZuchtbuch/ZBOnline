<?php

namespace App\queries\report\parent;

use App\queries\Query;

class Select
{
    public static function execute( int $id ) : ? array { // not the reportId ( not id )
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            SELECT * FROM report_parent WHERE id=:id
        ' );
        return Query::select( $stmt, $args );
    }
}