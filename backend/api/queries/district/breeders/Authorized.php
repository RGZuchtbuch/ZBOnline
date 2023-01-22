<?php

namespace App\queries\district\breeders;

use App\queries\Query;
use http\Exception\BadMessageException;

class Authorized extends Query
{
    public static function execute( ...$args ) : bool {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            SELECT userId FROM admin WHERE userId=:requesterId
            UNION ALL
            SELECT userId FROM moderator WHERE moderator.userId=:requesterId AND moderator.districtId=:districtId
            UNION ALL
            SELECT id FROM user WHERE user.id=:requesterId
        ' );
        return static::select( $stmt, $args ) != null; // false if no combi found
    }

    private static function validate( int $requesterId, int $districtId ) : array {
        if( $requesterId > 0 && $districtId > 0 ) {
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}