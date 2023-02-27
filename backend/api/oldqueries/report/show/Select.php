<?php

namespace App\queries\report\show;

use App\queries\BaseModel;
use http\Exception\BadMessageException;

class Select extends BaseModel
{
    public static function execute( ...$args ) : ? array {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            SELECT * FROM report_show WHERE reportId=:reportId
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