<?php

namespace App\queries\club;

use App\queries\Query;
use http\Exception\BadMessageException;

class Delete extends Query
{
    public static function execute ( ...$args ) : bool {
        $args = static::validate( ...$args ); // all vars in scope

        $stmt = static::prepare( '
            DELETE FROM club WHERE id=:clubId
        ' );

        return static::delete( $stmt, $args );
    }

    private static function validate( int $id ) {
        if( $id >0 ) {
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}