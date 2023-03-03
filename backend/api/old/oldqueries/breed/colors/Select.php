<?php

namespace App\queries\breed\colors;

use App\queries\BaseModel;
use http\Exception\BadMessageException;

// get colors for a breedId
class Select extends BaseModel
{
    public static function execute( ...$args ) : ? array {
        $args = static::validate( ...$args ); // all vars in scope
        $stmt = static::prepare( '
            SELECT * FROM color WHERE breedId=:breedId ORDER BY name
        ' );
        return static::selectArray( $stmt, $args );
    }

    private static function validate( int $breedId ) : array {
        if( $breedId > 0 ) {
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}
