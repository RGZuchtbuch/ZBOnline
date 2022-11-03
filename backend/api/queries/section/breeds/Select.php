<?php

namespace App\queries\section\breeds;

use App\queries\Query;
use http\Exception\BadMessageException;

class Select extends Query
{
    public static function execute( ...$args ) : ? array {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            SELECT breed.* 
            FROM breed
            WHERE sectionId=:sectionId OR subsectionId = :subsectionId
            ORDER BY name
        ' );
        return static::selectArray( $stmt, $args );
    }

    private static function validate( int $sectionId ) : array {
        if( $sectionId>0 ) {
            $subsectionId = $sectionId; // needs sectionId twice
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}