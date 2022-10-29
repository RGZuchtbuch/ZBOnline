<?php

namespace App\queries\district;

use App\queries\Query;

class Update
{
    public static function execute (
        int $id, string $name, string $fullname, string $short, string $coordinates
    ) : bool {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            UPDATE district 
            SET name=:name, fullname=:fullname, short=:short, coordinates=:coordinates
            WHERE id=:id   
        ' );
        return Query::update( $stmt, $args );
    }
}