<?php

namespace App\queries\club;

use App\queries\BaseModel;
use http\Exception\BadMessageException;

class Delete extends BaseModel
{
    public static function execute ( ...$args ) : bool {
        $args = static::validate( ...$args ); // all vars in scope

        $stmt = static::prepare( '
            DELETE FROM club WHERE id=:clubId
        ' );

        return static::delete( $stmt, $args );
    }

    private static function validate( int $id ) : array {
        if( $id >0 ) {
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}