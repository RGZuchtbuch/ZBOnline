<?php

namespace App\Query;

use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpNotImplementedException;

class District extends Query
{

    public static function new(
        int $parentId, string $name, ? string $fullname, ? string $short, // parentId cannot change
        ? float $latitude, ? float $longitude,
        string $level, ? int $moderatorId, int $modifier
    ) : ? int {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            INSERT INTO district ( parentId, name, fullname, short, latitude, longitude, level, moderatorId, modifier )
            VALUES ( :parentId, :name, :fullname, :short, :latitude, :longitude, :level, :moderatorId, :modifier )
        ' );
        return Query::insert( $stmt, $args );
    }

    public static function get( int $id ) : ? array {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            SELECT district.* 
            FROM district
            WHERE id=:id
        ' );
        return Query::select($stmt, $args);
    }

    public static function set(
        int $id,
        string $name, ? string $fullname, ? string $short,
        ? float $latitude, ? float $longitude,
        string $level, ? int $moderatorId, int $modifier
    ) : bool {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            UPDATE district
            SET name=:name, fullname=:fullname, short=:short, latitude=:latitude, longitude=:longitude, level=:level, moderatorId=:moderatorId, modifier=:modifier
            WHERE id=:id  
        ');
        return Query::update($stmt, $args);
    }

    public static function del( int $id ) : bool {
        throw new HttpNotImplementedException( null, "oops" );
    }

    /**
     * @param int $districtId
     * @return array of breeders in district incl descendants
     */
    public static function breeders( int $districtId ) : array {
        $args = get_defined_vars();
        $stmt = Query::prepare( "
            SELECT 
                breeder.id, breeder.firstname, breeder.infix, breeder.lastname, 
                district.id AS districtId, district.name AS districtName,
                club.id AS clubId, club.name AS clubName, start, end
			FROM user AS breeder
                LEFT JOIN district ON district.id=breeder.districtId 
                LEFT JOIN district AS club ON club.id=breeder.clubId 
            WHERE breeder.districtId IN (
                SELECT DISTINCT child.id FROM district AS parent
                    LEFT JOIN district AS child ON child.id = parent.id OR child.parentId = parent.id
                WHERE parent.id=:districtId OR parent.parentId = :districtId  
            )
            ORDER BY breeder.lastName, breeder.firstName
        ");
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

    public static function descendants( int $districtId ) : array {
        $args = get_defined_vars();
        $stmt = Query::prepare( "
            SELECT DISTINCT child.* FROM district AS parent
                LEFT JOIN district AS child ON child.id = parent.id OR child.parentId = parent.id
            WHERE parent.id=:districtId OR parent.parentId=:districtId
            ORDER BY name
        ");
        return Query::selectArray( $stmt, $args );
    }

    /*
     * getting results for pigeons for results edit
     */
    public static function breedResult( int $districtId, int $breedId, int $year, string $group ) : array {
        $args = get_defined_vars();
        $stmt = Query::prepare("
            SELECT 
                   result.id, result.reportId, :districtId AS districtId, :year AS `year`, :group AS `group`,             
                   breed.id AS breedId, null AS colorId, 'Gesamte Farbenschläge' AS name,  
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
        ");
        return Query::selectArray( $stmt, $args );
    }

    /*
     * get result for a breed's colors, so for non pigeons edit
     */
    public static function colorResults( int $districtId, int $breedId, int $year, string $group ) : array {
        $args = get_defined_vars();
        $stmt = Query::prepare("
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
        ");
        return Query::selectArray( $stmt, $args );
    }

    public static function sectionResults( int $districtId, int $sectionId, int $year, string $group ) : array {
        $args = get_defined_vars();

        $stmt = Query::prepare("
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
				SELECT child.id FROM section AS parent
					LEFT JOIN section AS child ON child.id = parent.id OR child.parentId = parent.id
				WHERE parent.id=:sectionId OR parent.parentId=:sectionId
            )
            GROUP BY breed.id, breed.name
            ORDER BY breed.name 
        ");

        return Query::selectArray( $stmt, $args );
    }

    public static function results( int $districtId, int $year ) : array {
        $args = get_defined_vars();
        $stmt = Query::prepare("
            SELECT COUNT(*),
                result.districtId AS districtId, section.id AS sectionId, subsection.id AS subsectionId, section.name AS sectionName, section.order, subsection.name AS subsectionName, subsection.order AS subsectionOrder,
                result.id, result.breedId, breed.name AS breedName, result.colorId, color.name AS colorName,
                CAST( SUM( result.breeders ) AS UNSIGNED ) AS breeders, CAST( SUM( result.pairs ) AS UNSIGNED ) AS pairs,
                CAST( SUM( result.layDames ) AS UNSIGNED ) AS layDames, AVG( result.layEggs ) AS layEggs, AVG( result.layWeight ) AS layWeight,
                CAST( SUM( result.broodEggs ) AS UNSIGNED ) AS broodEggs, CAST( SUM( result.broodFertile ) AS UNSIGNED ) AS broodFertile, CAST( SUM( result.broodHatched ) AS UNSIGNED ) AS broodHatched, 
                CAST( SUM( result.showCount ) AS UNSIGNED ) AS showCount, AVG( result.showScore ) AS showScore
            
            FROM result
            LEFT JOIN breed ON breed.id = result.breedId
            LEFT JOIN color ON color.id = result.colorId
            LEFT JOIN section AS subsection ON subsection.id = breed.sectionId
            LEFT JOIN section ON section.id = subsection.parentId
            
            WHERE result.year=:year AND result.districtId IN (
                SELECT child.id FROM district AS parent
                    LEFT JOIN district AS child ON child.id = parent.id OR child.parentId = parent.id
                WHERE parent.id=:districtId OR parent.parentId = :districtId                
            )
            
            GROUP BY subsection.order, breed.name, color.name
            ORDER BY subsection.order, breed.name, color.name
        ");

        return Query::selectArray( $stmt, $args );
    }
}
