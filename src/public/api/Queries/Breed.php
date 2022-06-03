<?php

namespace App\Queries;

class Breed
{

    public static function get( int $id ) : ? array {
        $args = [ 'id'=>$id ];
        $stmt = Query::prepare( '
            SELECT * FROM std_breed WHERE id=:id
        ' );
        return Query::select( $stmt, $args );
    }

    public static function getColors( int $breedId ) : array {
        $args = [ 'breedId'=>$breedId ];
        $stmt = Query::prepare( '
            SELECT * FROM std_color WHERE breed=:breedId
        ' );
        return Query::selectArray( $stmt, $args );
    }

}