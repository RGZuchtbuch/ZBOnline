<?php

namespace App\queries\district;

use App\queries\Query;

class Insert
{
    public static function execute (
        int $parentId, string $name, string $fullname, string $short, string $coordinates
    ) : bool {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            INSERT INTO district ( parentId, name, fullname, short, coordinates )
            VALUES ( :parentId, :name, :fullname, :short, :coordinates )
        ' );
        return Query::insert( $stmt, $args );
    }
}