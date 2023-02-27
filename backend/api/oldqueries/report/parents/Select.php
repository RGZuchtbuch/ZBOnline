<?php

namespace App\queries\report\parents;

use App\queries\BaseModel;
use http\Exception\BadMessageException;

class Select extends BaseModel
{
    public static function execute( ...$args ) : ? array { // not the reportId ( not id )
        $args = static::validate( ...$args );
        $stmt = BaseModel::prepare( '
            SELECT * FROM report_parent WHERE reportId=:reportId
        ' );
        return BaseModel::selectArray( $stmt, $args );
    }
    private static function validate( int $reportId ) : array {
        if( $reportId>0  ) {
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}