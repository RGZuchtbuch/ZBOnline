<?php

namespace App\queries\moderator;

use App\queries\Query;
use App\routes\Controller;
use http\Exception\BadMessageException;

class Delete extends Query
{
    public static function execute ( ...$args ) : bool {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            DELETE FROM moderator
            WHERE userId=:userId AND districtId=:districtId
        ' );
        return static::delete( $stmt, $args );
    }

    private static function validate( int $userId, int $districtId ) : array {
        if( $userId>0 && $districtId>0 ) {
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}