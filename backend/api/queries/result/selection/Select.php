<?php

namespace App\queries\result\selection;

use App\queries\Query;

class Select
{
    public static function execute( int $districtId, int $sectionId, int $year, string $group ) : array {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            SELECT breed.id, breed.name, result.* 
            FROM breed
            LEFT JOIN result ON result.breedId = breed.id AND result.districtId=:districtId AND result.year=:year AND result.group=:group AND result.reportId IS NULL
            WHERE  breed.sectionId=:sectionId
            ORDER BY breed.name
        ' );
        return Query::selectArray( $stmt, $args );
    }
}