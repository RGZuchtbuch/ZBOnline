<?php

namespace App\queries\breeder\reports;

use App\queries\Query;

class Select
{
    public static function execute( int $breederId ) : ? array {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            SELECT *
            FROM report
            WHERE breederId=:breederId
            ORDER BY year, name 
        ' );
        return Query::selectArray( $stmt, $args );
    }
}