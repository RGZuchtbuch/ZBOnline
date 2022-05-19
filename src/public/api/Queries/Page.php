<?php

namespace App\Queries;

class Page
{
    /**
     * @param int $id
     * @return array|null
     */
    public static function get( int $id ) : ? array {
        $args = [ 'id'=>$id ];
        $stmt = Query::prepare( 'SELECT * FROM page WHERE id=:id' );
        return Query::get( $stmt, $args );
    }
}
