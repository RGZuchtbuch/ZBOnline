<?php

namespace App\queries\district;

use App\queries\Query;
use App\controllers\Controller;
use http\Exception\BadMessageException;

class Insert extends Query
{
    public static function execute ( ...$args ) : bool {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            INSERT INTO district ( parentId, name, fullname, short, latitude, longitude, modifier )
            VALUES ( :parentId, :name, :fullname, :short, :latitude, :longitude, :modifier )
        ' );
        return static::insert( $stmt, $args );
    }

    private static function validate( int $parentId, string $name, string $fullname, string $short, float $latitude, float $longitude ) : array {
        if( $parentId>0 && strlen($name)>2 && strlen($fullname)>2 && strlen($short)>0 && $latitude>45 && $latitude<55 && $longitude>5 && $longitude<15 ) {
            $modifier = Controller::$requester['id']; // add to def vars
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}