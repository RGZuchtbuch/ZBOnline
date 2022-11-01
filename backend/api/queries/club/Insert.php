<?php

namespace App\queries\club;

use App\queries\Query;
use App\routes\Controller;
use http\Exception\BadMessageException;

class Insert extends Query
{

    // override
    public static function execute ( ...$args ) : bool {
        $args = static::validate( ...$args );

        $stmt = static::prepare( '
            INSERT INTO club ( name, city, modifier )
            VALUES ( :name, :city, :modifier )
        ' );
        return static::insert( $stmt, $args );
    }

    private static function validate( string $name, string $city ) {
        $modifier = Controller::$requester['id']; // add to def vars
        if( strlen( $name ) > 3 && strlen( $city ) > 3 ) {
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}