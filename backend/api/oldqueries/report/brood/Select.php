<?php

namespace App\queries\report\brood;

use App\queries\BaseModel;
use App\routes\Controller;
use http\Exception\BadMessageException;

class Select extends BaseModel
{
    public static function execute( ...$args ) : ? array { // not the reportId ( not id )
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            SELECT * FROM report_brood WHERE id=:id
        ' );
        return static::select( $stmt, $args );
    }

    private static function validate( int $id ) : array {
        if( $id>0 ) {
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}