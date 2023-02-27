<?php

namespace App\queries\breed;

use App\queries\BaseModel;
use http\Exception\BadMessageException;

class Delete extends BaseModel
{
    public static function execute ( ...$args ) : bool {
        $args = static::validate( ...$args ); // all vars in scope
        $stmt = static::prepare( '
            DELETE FROM Breed WHERE id=:breedId
        ' );
        return static::delete( $stmt, $args );
    }

    private static function validate( int $breedId ) : array {
        if( $breedId > 0 ) {
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}