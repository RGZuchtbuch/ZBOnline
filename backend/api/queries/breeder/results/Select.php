<?php

namespace App\queries\breeder\results;

use App\queries\Query;

class Select
{
    public static function execute( int $breederId ) : ? array {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            SELECT result.*
            FROM result
            LEFT JOIN report ON report.id = result.reportId
            WHERE report.breederId=:breederId
        ' );
        return Query::select( $stmt, $args );
    }
}