<?php

namespace App\queries\report\lay;

use App\queries\Query;

class Insert
{
    public static function execute (
        int $reportId, string $start, string $end, ? int $eggs, ? int $dames, ? int $weight
    ) : bool {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            INSERT INTO report_lay ( reportId, `start`, `end`, eggs, dames, weight )
            VALUES ( :reportId, :start, :end, :eggs, :dames, :weight )
        ' );
        return Query::insert( $stmt, $args );
    }
}