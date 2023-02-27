<?php

namespace App\queries\section\breeds;

use App\queries\BaseModel;
use http\Exception\BadMessageException;

class Select extends BaseModel
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
            
            SELECT Breed.id, Breed.name, sections.name AS sectionName
            FROM sections
            LEFT JOIN Breed ON Breed.sectionId=sections.childId
            WHERE Breed.id IS NOT NULL
            ORDER BY Breed.name
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