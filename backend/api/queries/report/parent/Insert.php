<?php

namespace App\queries\report\parents;

use App\queries\Query;

class Insert
{
    public static function execute (
        int $reportId, string $sex, string $ring, ? float $score
    ) : bool {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            INSERT INTO report_parent ( reportId, sex, ring, score )
            VALUES ( :reportId, :sex, :ring, :score )
        ' );
        return Query::insert( $stmt, $args );
    }
}