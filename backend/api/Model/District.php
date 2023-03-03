<?php

namespace App\Model;



use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpNotImplementedException;

class District extends Model
{
    public static function new( string $name, int $sectionId, int $broodGroup, int $lay, int $eggWeight, int $sireRing, int $dameRing, int $sireWeight, int $dameWeight, string $info ) : ? int {
        throw new HttpNotImplementedException( null, "oops" );
    }

    public static function get( int $id ) : ? array {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            SELECT district.*, moderator.userId as moderatorId 
            FROM district
            LEFT JOIN moderator ON moderator.districtId = district.id
            WHERE id=:id
        ' );
        return Query::select($stmt, $args);
    }


    public static function set( int $id, string $name, int $sectionId, int $broodGroup, int $lay, int $eggWeight, int $sireRing, int $dameRing, int $sireWeight, int $dameWeight, string $info ) : bool {
        throw new HttpNotImplementedException( null, "oops" );
    }

    public static function del( int $id ) : bool {
        throw new HttpNotImplementedException( null, "oops" );
    }

    public static function breeders( int $id ) : array {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            SELECT user.id, firstname, infix, lastname, email, districtId, clubId, club.name AS clubName, start, end 
            FROM user 
            LEFT JOIN district AS club ON club.id = user.clubId
            WHERE districtId=:id
            ORDER BY lastname
        ');
        return Query::selectArray( $stmt, $args );
    }

    public static function children( int $id ) : array {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            SELECT id, parentId, name FROM district WHERE id=:id
            UNION
            SELECT id, parentId, name FROM district WHERE parentId=:id
            ORDER BY name
        ');
        return Query::selectArray( $stmt, $args );
    }

    public static function descendants( int $id ) : array {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            WITH RECURSIVE districts( id, parentId, name, moderatable, level ) AS (
               SELECT id, parentId, name, moderatable, level FROM district WHERE id=:id
               UNION
               SELECT district.id, district.parentId, district.name, district.moderatable, district.level
               FROM districts JOIN district ON district.parentId=districts.id
            )
            SELECT * FROM districts
            ORDER BY districts.name
        ' );
        return Query::selectArray( $stmt, $args );
    }

    public static function breedResult( int $districtId, int $breedId, int $year, string $group ) : array {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            SELECT 
                   result.id, result.reportId, :districtId AS districtId, :year AS `year`, :group AS `group`,             
                   breed.id AS breedId, null AS colorId, "gesamt" AS name,  
                   result.breeders, result.pairs,
                   result.layDames, result.layEggs, result.layWeight,
                   result.broodEggs, result.broodFertile, result.broodHatched,
                   result.showCount, result.showScore
            FROM breed
            LEFT JOIN result ON result.breedId = breed.id AND result.colorId IS NULL
                AND result.districtId = :districtId
                AND result.year = :year
                AND result.group = :group  
            WHERE breed.id = :breedId
        ');
        return Query::selectArray( $stmt, $args );
    }

    public static function colorResults( int $districtId, int $breedId, int $year, string $group ) : array {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            SELECT result.id, result.reportId, :districtId AS districtId, :year AS `year`, :group AS `group`,
                   breed.id AS breedId, color.id AS colorId, color.name,  
                   result.breeders, result.pairs,
                   result.layDames, result.layEggs, result.layWeight,
                   result.broodEggs, result.broodFertile, result.broodHatched,
                   result.showCount, result.showScore
            FROM breed
            LEFT JOIN color ON color.breedId = breed.id    
            LEFT JOIN result ON result.breedId = breed.id AND result.colorId = color.id
                AND result.districtId = :districtId
                AND result.year = :year
                AND result.group = :group             
            WHERE breed.id = :breedId
            ORDER BY color.name 
        ');
        return Query::selectArray( $stmt, $args );
    }

    public static function sectionResults( int $districtId, int $sectionId, int $year, string $group ) : array {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            SELECT 
          		breed.id, breed.name, 
          		COUNT( result.id ) AS results, :districtId AS districtId, :year AS `year`, :group AS `group`, 
                SUM( breeders ) AS breeders, SUM( pairs ) AS pairs,
                SUM( result.layDames ) AS layDames, AVG( layEggs ) AS layEggs, AVG( result.layWeight ) AS layWeight,
                SUM( result.broodEggs) AS broodEggs, SUM( broodFertile ) AS broodFertile, SUM( broodHatched ) AS broodHatched,
                SUM( result.showCount ) AS showCount, AVG( showScore ) AS showScore
            FROM breed
            LEFT JOIN result ON result.breedId = breed.id
                AND result.districtId = :districtId
                AND result.year = :year
                AND result.group = :group
            WHERE breed.sectionId IN (
                WITH RECURSIVE sections( id ) AS 
                (
                    SELECT id FROM section WHERE id = :sectionId
                    UNION
                    SELECT section.id
                    FROM sections 
                    JOIN section ON section.parentId = sections.id
                )   
                SELECT id FROM sections
            )
            GROUP BY breed.id
            ORDER BY breed.name 
        ');
        return Query::selectArray( $stmt, $args );
    }

    public static function results( int $id, int $year ) : array {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            WITH RECURSIVE districts( id, childId, name ) AS 
            (
                SELECT id, id AS childId, name FROM district WHERE id=:id
                UNION
                SELECT districts.id AS id, district.id AS childId, districts.name AS name
                FROM districts JOIN district ON district.parentId=districts.childId
            )
            
            SELECT districts.id AS districtId, sections.id AS sectionId, sections.name AS sectionName, 
                results.id, results.breedId, results.breedName, results.colorId, results.colorName,
                CAST( SUM( results.breeders ) AS UNSIGNED ) AS breeders, CAST( SUM( results.pairs ) AS UNSIGNED ) AS pairs,
                CAST( SUM( results.layDames ) AS UNSIGNED ) AS layDames, AVG( results.layEggs ) AS layEggs, AVG( results.layWeight ) AS layWeight,
                CAST( SUM( results.broodEggs ) AS UNSIGNED ) AS broodEggs, CAST( SUM( results.broodFertile ) AS UNSIGNED ) AS broodFertile, CAST( SUM( results.broodHatched ) AS UNSIGNED ) AS broodHatched, 
                CAST( SUM( results.showCount ) AS UNSIGNED ) AS showCount, AVG( results.showScore ) AS showScore
            FROM districts
            
            JOIN (
                WITH RECURSIVE sections( id, childId, NAME, `order` ) AS 
                (
                    SELECT id, id AS childId, NAME, `order` FROM section WHERE id IN ( 3, 11, 12, 13, 5, 6 )
                     UNION
                    SELECT sections.id AS id, section.id AS childId, sections.name AS name, sections.order AS `order`
                    FROM sections JOIN section ON section.parentId=sections.childId
                )
                SELECT * FROM sections
            ) AS sections
            
            LEFT JOIN 
            (
                SELECT result.*, Breed.sectionId AS sectionId, Breed.name AS breedName, color.name AS colorName FROM result 
                LEFT JOIN Breed ON Breed.id=result.breedId
                LEFT JOIN color ON color.id=result.colorId
                WHERE result.year=:year
            ) AS results ON  results.districtId=districts.childId AND results.sectionId=sections.childId
            WHERE results.breedId IS NOT NULL
            GROUP BY sections.id, results.breedId, results.colorId
            ORDER BY sections.order, results.breedName, results.colorName
        ');
        return Query::selectArray( $stmt, $args );
    }
}
