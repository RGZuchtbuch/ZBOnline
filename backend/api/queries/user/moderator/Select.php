<?php

namespace App\queries\user\moderator;

use App\queries\Query;
use http\Exception\BadMessageException;


/**
 * Selects array of districtId's that the user by id is moderator
 * @args id
 * @returns array of districtId's
 */
class Select extends Query
{
    public static function execute( ...$args ) : ? array {
        $args = static::validate( ...$args );
        $stmt = static::prepare( 'SELECT districtId FROM moderator WHERE userId=:id' );
        $districts = static::selectArray( $stmt, $args );
        return array_column( $districts, 'districtId' ); // only list of district id's needed
    }

    private static function validate( int $id ) : array {
        if( $id>0 ) {
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}
