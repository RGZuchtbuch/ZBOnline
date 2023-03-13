<?php

namespace App\queries\district\section\results;

use App\queries\BaseModel;

class Select
{
    public static function execute( int $districtId, int $sectionId, int $year, string $group ) : array {
        $args = get_defined_vars(); // all vars in scope
        $args['aDistrictId'] = $districtId; // to ensure valid district if result not exists
        $args['aYear'] = $year;
        $args['aGroup'] = $group;
        $stmt = BaseModel::prepare( '
                
                    WITH RECURSIVE sections( id, childId, NAME ) AS 
                    (
                        SELECT id, id AS childId, NAME FROM section WHERE id=:sectionId
                         UNION
                        SELECT sections.id AS id, section.id AS childId, sections.name AS name
                        FROM sections JOIN section ON section.parentId=sections.childId
                    )
                SELECT results.*, sections.id AS sectionId, sections.name AS sectionName
                FROM Breed
                
                JOIN ( # get section with subsections
                    SELECT * FROM sections
                ) AS sections
                
                LEFT JOIN 
                (
                    SELECT result.*, Breed.sectionId AS sectionId, Breed.name AS breedName FROM result 
                    LEFT JOIN Breed ON Breed.id=result.breedId
                    WHERE result.year=:year
                ) AS results ON results.districtId=district.id AND results.sectionId=sections.childId
                
                WHERE district.id=:districtId 
                GROUP BY result.idsections.id, results.breedName
                ORDER BY results.breedName
        ' );
        return BaseModel::selectArray( $stmt, $args );
    }
}

/*
            SELECT Breed.id, Breed.name,
                   result.id AS resultId, reportId, :aDistrictId AS districtId, breeders, pairs, :aYear AS `year`, :aGroup AS `group`,
                   result.sectionId, breedId, colorId,
                   result.layDames, layEggs, layWeight,
                   result.broodEggs, broodFertile, broodHatched,
                   result.showCount, showScore
            FROM Breed
            LEFT JOIN result
                ON result.breedId=Breed.id AND result.colorId IS NULL
                AND result.districtId=:districtId
                AND result.year=:year
                AND result.group=:group
            WHERE Breed.sectionId=:sectionId
            ORDER BY Breed.name
 */