<?php

namespace App\Queries;

class Districts
{
    public static function get( int $id ) : ? array {
        $args = [ 'moderatorId'=>$id ];
        $stmt = Query::prepare( '
            SELECT district.*
            FROM district
            LEFT JOIN moderator ON moderator.district=district.id
            WHERE moderator.id=:moderatorId
        ' );
        return Query::selectArray( $stmt, $args );
    }
}