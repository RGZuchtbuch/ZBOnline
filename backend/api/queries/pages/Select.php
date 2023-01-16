<?php

namespace App\queries\pages;

use App\queries\Query;
use http\Exception\BadMessageException;

class Select extends Query
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
