<?php

namespace App\queries\breeder\results;

use App\queries\Query;
use http\Exception\BadMessageException;

class Select extends Query
{
    public static function execute( ...$args ) : ? array {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            SELECT result.*
            FROM result
            LEFT JOIN report ON report.id = result.reportId
            WHERE report.breederId=:breederId
        ' );
        return static::select( $stmt, $args );
    }

    private static function validate( int $breederId ) : array {
        if( $breederId > 0 ) {
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}