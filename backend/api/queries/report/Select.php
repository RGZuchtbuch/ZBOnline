<?php

namespace App\queries\report;

use App\queries\Query;
use http\Exception\BadMessageException;

class Select extends Query
{
    public static function execute( ...$args ) : ? array {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            SELECT * FROM report WHERE id=:reportId
        ' );
        return static::select( $stmt, $args );
    }

    private static function validate( int $reportId  ) : array {
        if( $reportId>0 ) {
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}