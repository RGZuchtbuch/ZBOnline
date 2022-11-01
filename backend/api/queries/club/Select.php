<?php

namespace App\queries\club;

use App\queries\Query;
use http\Exception\BadMessageException;

class Select extends Query
{

    public static function execute( ...$args ) : ? array {
        $args = static::validate( ...$args );

        $stmt = static::prepare( '
            SELECT * FROM club WHERE id=:id
        ' );
        return static::select( $stmt, $args );
    }

    protected static function validate( int $id ) {
        if( $id > 0 ) {
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}