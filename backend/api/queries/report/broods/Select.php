<?php

namespace App\queries\report\broods;

use App\queries\Query;
use App\routes\Controller;
use http\Exception\BadMessageException;

class Select extends Query
{
    public static function execute( ...$args ) : ? array { // not the reportId ( not id )
        $args = static::validate( ...$args );
        $stmt = Query::prepare( '
            SELECT * FROM report_brood WHERE reportId=:reportId
        ' );
        return Query::selectArray( $stmt, $args );
    }
    private static function validate( int $reportId ) : array {
        if( $reportId>0  ) {
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}