<?php

namespace App\queries\breed;

use App\queries\Query;

class Delete
{
    public static function execute ( int $breedId ) : bool {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            DELETE FROM breed WHERE id=:breedId
        ' );
        return Query::delete( $stmt, $args );
    }
}