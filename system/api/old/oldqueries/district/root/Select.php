<?php

namespace App\Queries\district\root;

use App\queries\BaseModel;
use http\Exception\BadMessageException;

class Select extends BaseModel
{
    public static function execute( $rootId ) : ? array {
        $args = static::validate( $rootId );
        $stmt = static::prepare( '
            WITH RECURSIVE districts( id, parentId, name, moderatable, level ) AS (
               SELECT id, parentId, name, moderatable, level FROM district WHERE id=:rootId
               UNION
               SELECT district.id, district.parentId, district.name, district.moderatable, district.level
               FROM districts JOIN district ON district.parentId=districts.id
            )
            SELECT * FROM districts
            ORDER BY districts.name
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