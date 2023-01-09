<?php

namespace App\queries\section\breeds;

use App\queries\Query;
use http\Exception\BadMessageException;

class Select extends Query
{
    public static function execute( ...$args ) : ? array {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            WITH RECURSIVE sections( id, childId, NAME ) AS 
            (
               SELECT id, id AS childId, NAME FROM section WHERE id=:sectionId
                UNION
               SELECT sections.id AS id, section.id AS childId, sections.name AS NAME #root name !
               FROM sections JOIN section ON section.parentId=sections.childId
            )
            
            SELECT breed.id, breed.name, sections.name AS sectionName
            FROM sections
            LEFT JOIN breed ON breed.sectionId=sections.childId
            WHERE breed.id IS NOT NULL
            ORDER BY breed.name
        ' );
        return static::selectArray( $stmt, $args );
    }

    private static function validate( int $sectionId ) : array {
        if( $sectionId>0 ) {
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}