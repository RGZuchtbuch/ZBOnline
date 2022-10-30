<?php

namespace App\queries\report\brood;

use App\queries\Query;

class Insert
{
    public static function execute (
        int $reportId, ? string $start, ? int $eggs, ? int $fertile, ? int $hatched
    ) : bool {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            INSERT INTO report_brood ( reportId, start, eggs, fertile, hatched )
            VALUES ( :reportId, :start, :eggs, :fertile, :hatched )
        ' );
        return Query::insert( $stmt, $args );
    }
}