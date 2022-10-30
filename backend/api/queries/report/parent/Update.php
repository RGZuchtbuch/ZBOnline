<?php

namespace App\queries\report\parents;

use App\queries\Query;

class Update
{
    public static function execute (
        int $id, int $reportId, string $sex, string $ring, ? float $score
    ) : bool {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            UPDATE report_parent 
            SET reportId=:name, sex=:sex, ring=:ring, score=:score
            WHERE id=:id   
        ' );
        return Query::update( $stmt, $args );
    }
}