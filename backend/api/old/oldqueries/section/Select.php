<?php

namespace App\queries\section;

use App\queries\BaseModel;
use http\Exception\BadMessageException;

class Select extends BaseModel
{
    public static function execute( ...$args ) : ? array {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            SELECT * FROM section WHERE id=:id
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