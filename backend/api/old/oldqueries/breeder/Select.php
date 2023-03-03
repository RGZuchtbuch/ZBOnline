<?php

namespace App\queries\breeder;

use App\queries\BaseModel;
use http\Exception\BadMessageException;

class Select extends BaseModel
{
    public static function execute( ...$args ) : ? array {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            SELECT user.id, user.name, email, districtId, district.name AS districtName, clubId, club.name AS clubName, start, end, active, info
            FROM user
            LEFT JOIN district ON district.id = districtId
            LEFT JOIN district AS club ON club.id = clubId
            WHERE user.id=:userId
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