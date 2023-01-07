<?php

namespace App\queries\district\section\breeds;

use App\queries\Query;

class Select
{
    public static function execute( int $districtId, int $sectionId, int $year, string $group ) : array {
        $args = get_defined_vars(); // all vars in scope
        $args['aDistrictId'] = $districtId; // to ensure valid district if result not exists
        $args['aYear'] = $year;
        $args['aGroup'] = $group;
        $stmt = Query::prepare( '
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
            ORDER BY breed.name
        ' );
        return Query::selectArray( $stmt, $args );
    }
}

/*
            SELECT breed.id, breed.name,
                   result.id AS resultId, reportId, :aDistrictId AS districtId, breeders, pairs, :aYear AS `year`, :aGroup AS `group`,
                   result.sectionId, breedId, colorId,
                   result.layDames, layEggs, layWeight,
                   result.broodEggs, broodFertile, broodHatched,
                   result.showCount, showScore
            FROM breed
            LEFT JOIN result
                ON result.breedId=breed.id AND result.colorId IS NULL
                AND result.districtId=:districtId
                AND result.year=:year
                AND result.group=:group
            WHERE breed.sectionId=:sectionId
            ORDER BY breed.name
 */