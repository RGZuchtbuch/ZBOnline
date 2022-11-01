<?php

namespace App\queries\breeder\reports;

use App\queries\Query;
use http\Exception\BadMessageException;

class Select extends Query
{
    public static function execute( ...$args ) : ? array {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            SELECT *
            FROM report
            WHERE breederId=:breederId
            ORDER BY year, name 
        ' );
        return static::selectArray( $stmt, $args );
    }

    private static function validate( int $breederId ) : array {
        if( $breederId > 0 ) {
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}