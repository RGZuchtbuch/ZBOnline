<?php

namespace App\queries\breed\colors;

use App\queries\Query;

class Select
{
    public static function execute( int $breedId ) : ? array {
        $args = [ 'breedId'=>$breedId ];
        $stmt = Query::prepare( '
            SELECT * FROM color WHERE breedId=:breedId ORDER BY name
        ' );
        return Query::selectArray( $stmt, $args );
    }
}
