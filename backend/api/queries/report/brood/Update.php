<?php

namespace App\queries\report\parents;

use App\queries\Query;

class Update
{
    public static function execute (
        int $id, int $reportId, ? string $start, ? int $eggs, ? int $fertile, ? int $hatched
    ) : bool {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            UPDATE report_brood 
            SET reportId=:reportId, start=:start, eggs=:eggs, fertile=:fertile, hatched=:hatched
            WHERE id=:id   
        ' );
        return Query::update( $stmt, $args );
    }
}