<?php

namespace App\queries\report\broods;

use App\queries\Query;
use App\routes\Controller;
use http\Exception\BadMessageException;

class Delete extends Query
{
    public static function execute ( ...$args  ) : bool {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            DELETE FROM report_brood WHERE reportId=:reportId
        ' );
        return static::delete( $stmt, $args );
    }

    private static function validate( int $reportId ) : array {
        if( $reportId>0 ) {
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}