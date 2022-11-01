<?php

namespace App\queries\club;

use App\queries\Query;
use App\routes\Controller;
use http\Exception\BadMessageException;

class Update extends Query
{
    public static function execute ( ...$args ) : bool {
        $args = static::validate( ...$args ); // all vars in scope

        $stmt = static::prepare( '
            UPDATE club 
            SET name=:name, city=:city, modifier=:modifier
            WHERE id=:id   
        ' );

        return static::update( $stmt, $args );
    }


    protected static function validate( int $id, string $name, string $city ) : array {
        $modifier = Controller::$requester['id']; // add to def vars
        if( $id>0 && strlen( $name )>2 && strlen( $city )>2) {
            return get_defined_vars();
        }
        throw new BadMessageException( "Error in query args");
    }
}