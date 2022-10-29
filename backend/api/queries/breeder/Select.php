<?php

namespace App\queries\breeder;

use App\queries\Query;

class Select
{
    public static function execute( int $userId ) : ? array {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            SELECT user.id, name, email, user.districtId, user.clubId
            FROM user
            WHERE user.id=:userId
        ' );
        return Query::select( $stmt, $args );
    }
}