<?php

namespace App\queries\section\tree;

use App\queries\Query;
use http\Exception\BadMessageException;

class Select extends Query
{
    public static function execute( ...$args ) : ? array {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            WITH RECURSIVE parent AS (
                SELECT * FROM std_section WHERE id=:parentId ORDER BY name
                UNION ALL
                SELECT child.* FROM parent, std_section AS child
			    WHERE child.parentId = parent.id ORDER BY child.name
            )
            SELECT * FROM parent
        ' );
        return static::selectArray( $stmt, $args );
    }

    private static function validate( int $parentId ) : array {
        if( $parentId>0 ) {
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}