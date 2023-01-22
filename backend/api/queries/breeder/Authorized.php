<?php

namespace App\queries\breeder;

use App\queries\Query;
use http\Exception\BadMessageException;

class Authorized extends Query
{
    public static function execute( ...$args ) : bool {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            SELECT user.id, user.districtId 
            FROM user
            LEFT JOIN moderator ON moderator.districtId = user.districtId
            WHERE user.id=:userId AND moderator.userId=:requesterId
        ' );
        return static::select( $stmt, $args ) != null; // false if no combi found
    }

    private static function validate( int $requesterId, int $userId ) : array {
        if( $requesterId > 0 && $userId > 0 ) {
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}