<?php

namespace App\Queries\section\root;

use App\queries\Query;
use http\Exception\BadMessageException;

class Select extends Query
{
    public static function execute( $rootId ) : ? array {
        $args = static::validate( $rootId );
        $stmt = static::prepare( '
            WITH RECURSIVE sections( id, parentId, name, layers, `order` ) AS (
               SELECT id, parentId, name, layers, `order` FROM section WHERE id=:rootId
               UNION
               SELECT section.id, section.parentId, section.name, section.layers, section.order FROM sections JOIN section ON section.parentId=sections.id
            )
            SELECT * FROM sections
            ORDER BY sections.order
        ' );
        return static::selectRoot( $stmt, $args );
    }

    private static function validate( int $rootId ) : array {
        if( $rootId > 0 ) {
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}