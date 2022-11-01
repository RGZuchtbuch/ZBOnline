<?php

namespace App\queries\color;

use App\queries\Query;
use App\routes\Controller;
use http\Exception\BadMessageException;

class Delete extends Query
{
    public static function execute ( ...$args ) : bool {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            DELETE FROM color WHERE id=:id
        ' );
        return static::delete( $stmt, $args );
    }

    private static function validate( int $id ) : array {
        if( $id>0 ) {
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}