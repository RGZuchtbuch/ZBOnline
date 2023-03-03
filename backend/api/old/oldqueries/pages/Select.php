<?php

namespace App\queries\pages;

use App\queries\BaseModel;
use http\Exception\BadMessageException;

class Select extends BaseModel
{
    public static function execute( ...$args ) : ? array {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            SELECT * FROM page
            ORDER BY modified DESC;
        ' );
        return static::selectArray( $stmt, $args );
    }

    private static function validate() : array {
        return get_defined_vars();
    }
}
