<?php

namespace App\queries\report\lay;

use App\queries\Query;
use http\Exception\BadMessageException;

class Select extends Query
{
    public static function execute( ...$args ) : ? array {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            SELECT * FROM report_lay WHERE reportId=:reportId
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