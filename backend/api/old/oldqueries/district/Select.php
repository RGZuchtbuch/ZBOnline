<?php

namespace App\queries\district;

use App\queries\BaseModel;
use App\routes\Controller;
use http\Exception\BadMessageException;

class Select extends BaseModel
{
    public static function execute( ...$args ) : ? array {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            SELECT district.*, moderator.userId as moderatorId 
            FROM district
            LEFT JOIN moderator ON moderator.districtId = district.id
            WHERE id=:id
        ' );
        return static::select( $stmt, $args );
    }

    private static function validate( int $id ) : array {
        if( $id>0 ) {
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}