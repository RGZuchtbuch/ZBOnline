<?php

namespace App\queries\breeder;

use App\queries\Query;
use http\Exception\BadMessageException;

class Select extends Query
{
    public static function execute( ...$args ) : ? array {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            SELECT id, name, email, districtId, clubId
            FROM user
            WHERE id=:userId
        ' );
        return static::select( $stmt, $args );
    }

    private static function validate( int $userId ) : array {
        if( $userId > 0 ) {
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}