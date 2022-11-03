<?php

namespace App\Queries\moderator\districts;

use App\queries\Query;
use http\Exception\BadMessageException;

class Select extends Query
{
    public static function execute( ...$args ) : ? array {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            SELECT district.*
            FROM district
            LEFT JOIN moderator ON moderator.districtId=district.id
            WHERE moderator.userId=:userId
        ' );
        return static::selectArray( $stmt, $args );
    }

    private static function validate( int $userId ) : array {
        if( $userId ) {
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}