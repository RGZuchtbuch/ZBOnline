<?php

namespace App\queries\breeder;

use App\queries\Query;
use App\controllers\Controller;
use http\Exception\BadMessageException;

class Insert extends Query
{
    public static function execute ( ...$args ) : bool {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            INSERT INTO user ( name, email, districtId, clubId, start, end, info, modifier )
            VALUES ( :name, :email, :districtId, :clubId, :start, :end, :info, :modifier )
        ' );
        return static::insert( $stmt, $args );
    }

    private static function validate( string $name, ? string $email, int $districtId, int $clubId, ? string $start, ? string $end, ? string $info ) : array {
        if( strlen( $name )>1 && $districtId>0 && $clubId>0 ) {
            $modifier = Controller::$requester['id']; // add to def vars
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}