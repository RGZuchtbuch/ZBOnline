<?php

namespace App\queries\color;

use App\queries\Query;
use App\routes\Controller;
use http\Exception\BadMessageException;

class Insert extends Query
{
    public static function execute ( ...$args ) : bool {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            INSERT INTO color ( breedId, name, info, modifier )
            VALUES ( :breedId, :name, :info, :modifier )
        ' );
        return static::insert( $stmt, $args );
    }

    private static function validate( int $breedId, string $name, string $info ) : array {
        if(
            $breedId>0 && strlen($name)>2 && strlen($info) < 100000
        ) {
            $modifier = Controller::$requester['id']; // add to def vars
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}