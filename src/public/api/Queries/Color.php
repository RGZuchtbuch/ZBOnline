<?php

namespace App\Queries;

class Color
{

    public static function get( int $id ) : ? array {
        $args = [ 'id'=>$id ];
        $stmt = Query::prepare( '
            SELECT * FROM std_color WHERE id=:id
        ' );
        return Query::select( $stmt, $args );
    }

}