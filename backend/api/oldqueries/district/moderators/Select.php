<?php

namespace App\queries\district\moderators;

use App\queries\BaseModel;
use App\routes\Controller;
use http\Exception\BadMessageException;

class Select extends BaseModel
{
    public static function execute( ...$args ) : ? array {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            SELECT * FROM moderator WHERE districtId=:districtId
        ' );
        return static::selectArray( $stmt, $args );
    }

    private static function validate( int $districtId ) : array {
        if( $districtId>0 ) {
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}