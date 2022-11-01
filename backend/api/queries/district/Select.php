<?php

namespace App\queries\district;

use App\queries\Query;
use App\routes\Controller;
use http\Exception\BadMessageException;

class Select extends Query
{
    public static function execute( ...$args ) : ? array {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            SELECT * FROM district WHERE id=:id
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