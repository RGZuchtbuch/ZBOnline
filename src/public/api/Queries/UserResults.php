<?php

namespace App\Queries;

class UserResults
{
    public static function get( int $id ) : ? array {
        $args = [ 'userId'=>$id ];
        $stmt = Query::prepare( '
            SELECT result.* 
            FROM result
            WHERE result.id=:userId
        ' );
        return Query::selectArray( $stmt, $args );
    }
}