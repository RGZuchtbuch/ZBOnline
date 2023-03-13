<?php

namespace App\queries\section\children;

use App\queries\BaseModel;
use http\Exception\BadMessageException;

class Select extends BaseModel
{
    public static function execute( ...$args ) : ? array {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            SELECT * FROM section WHERE parentId=:parentId ORDER BY name
        ' );
        return static::selectArray( $stmt, $args );
    }

    private static function validate( int $parentId ) : array {
        if( $parentId>0 ) {
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}