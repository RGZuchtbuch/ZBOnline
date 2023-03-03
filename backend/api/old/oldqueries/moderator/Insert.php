<?php

namespace App\queries\moderator;

use App\queries\BaseModel;
use App\routes\Controller;
use http\Exception\BadMessageException;

class Insert extends BaseModel
{
    public static function execute ( ...$args ) : bool {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            INSERT INTO moderator ( userId, districtId, modifier )
            VALUES ( :userId, :districtId, :modifier )
        ' );
        return static::insert( $stmt, $args );
    }

    private static function validate( int $userId, int $districtId ) : array {
        if( $userId>0 && $districtId>0 ) {
            $modifier = Controller::$requester['id']; // add to def vars
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}