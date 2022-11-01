<?php

namespace App\queries\breeder;

use App\queries\Query;
use http\Exception\BadMessageException;

class Select extends Query
{
    public static function execute( ...$args ) : ? array {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            SELECT user.id, name, email, user.districtId, user.clubId
            FROM user
            WHERE user.id=:userId
        ' );
        return static::select( $stmt, $args );
    }

    private static function validate( $userId ) : array {
        if( $userId > 0 ) {
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}