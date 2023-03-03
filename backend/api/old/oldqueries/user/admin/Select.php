<?php

namespace App\queries\user\admin;

use App\queries\BaseModel;
use http\Exception\BadMessageException;


/**
 * Selects array of districtId's that the user by id is moderator
 * @args id
 * @returns array of districtId's
 */
class Select extends BaseModel
{
    public static function execute( ...$args ) : ? bool {
        $args = static::validate( ...$args );
        $stmt = static::prepare( 'SELECT userId FROM admin WHERE userId=:userId' );
        return static::select($stmt, $args ) != null; // true or false
    }

    private static function validate( int $userId ) : array {
        if( $userId>0 ) {
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}
