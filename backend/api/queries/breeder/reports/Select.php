<?php

namespace App\queries\breeder\reports;

use App\queries\Query;
use http\Exception\BadMessageException;

class Select extends Query
{
    public static function execute( ...$args ) : ? array {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            SELECT result.*, report.name AS reportName, breed.name AS breedName, color.name AS colorName
            FROM result
            LEFT JOIN report ON report.id = result.reportId
            LEFT JOIN breed ON breed.id = result.breedId
            LEFT JOIN color ON color.id = result.colorId
            WHERE report.breederId=:breederId
            ORDER BY result.year DESC , report.name 
        ' );
        return static::selectArray( $stmt, $args );
    }

    private static function validate( int $breederId ) : array {
        if( $breederId > 0 ) {
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}