<?php

namespace App\Model;



use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpNotImplementedException;

class District extends Model
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
                districts.id AS districtId, districts.name AS districtName,
                club.id AS clubId, club.name AS clubName
			FROM user AS breeder 
			JOIN (
                SELECT id, parentId, name, level, moderatorId
                FROM (
                	SELECT @root:=:districtId, @districtId:=:districtId # insert districtId here 
				) AS init, (
                    SELECT * FROM district ORDER BY parentId, id
                ) AS sorted				
                WHERE ( find_in_set(parentId, @districtId) > 0 AND @districtId := CONCAT(@districtId, ',', id) ) OR id=@root
			) AS districts ON districts.id = breeder.districtId
			LEFT JOIN district AS club ON club.id = breeder.clubId
            ORDER BY breeder.lastname, breeder.firstname 
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

    public static function descendants( int $id ) : array {
        $args = get_defined_vars();
        $stmt = Query::prepare( "
            SELECT *
            FROM (
                SELECT @root:=:id, @parents:=@root
            ) AS init, (
                SELECT id, parentId, name, level, moderatorId
                FROM district ORDER BY parentId, id
            ) AS sorted 
            WHERE ( find_in_set(parentId, @parents) > 0 AND @parents := CONCAT(@parents, ',', id) ) OR id=@root
            ORDER BY sorted.name
        ");
/*
        $stmt = Query::prepare( '
            WITH RECURSIVE districts( id, parentId, name, level, moderatorId ) AS (
               SELECT id, parentId, name, level, moderatorId FROM district WHERE id=:id
               UNION
               SELECT district.id, district.parentId, district.name, district.level, district.moderatorId
               FROM districts JOIN district ON district.parentId=districts.id
            )
            SELECT * FROM districts
            ORDER BY districts.name
        ' );
*/
        return Query::selectArray( $stmt, $args );
    }

    /*
     * getting results for pigeons for results edit
     */
    public static function breedResult( int $districtId, int $breedId, int $year, string $group ) : array {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            SELECT 
                   result.id, result.reportId, :districtId AS districtId, :year AS `year`, :group AS `group`,             
                   breed.id AS breedId, null AS colorId, null AS name,  
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

    /*
     * get result for a breed's colors, so for non pigeons edit
     */
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
                SELECT id
                FROM (
                    SELECT @pv:=:sectionId AS root
                ) init, (
                    select id, parentId from section ORDER BY parentId, id
                ) sorted 
                WHERE ( find_in_set(parentId, @pv) > 0 AND @pv := CONCAT(@pv, ',', id) ) OR id=:sectionId     
            )
            GROUP BY breed.id, breed.name
            ORDER BY breed.name 
        ");

/*
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
            GROUP BY breed.id, breed.name
            ORDER BY breed.name 
        ');
*/
        return Query::selectArray( $stmt, $args );
    }

    public static function results( int $districtId, int $year ) : array {
        $args = get_defined_vars();
        $stmt = Query::prepare("
             SELECT COUNT(*) AS `count`,
				districts.id AS districtId, sections.rootId AS sectionId, sections.name AS sectionName, sections.order,
                results.id, results.breedId, results.breedName, results.colorId, results.colorName,
                CAST( SUM( results.breeders ) AS UNSIGNED ) AS breeders, CAST( SUM( results.pairs ) AS UNSIGNED ) AS pairs,
                CAST( SUM( results.layDames ) AS UNSIGNED ) AS layDames, AVG( results.layEggs ) AS layEggs, AVG( results.layWeight ) AS layWeight,
                CAST( SUM( results.broodEggs ) AS UNSIGNED ) AS broodEggs, CAST( SUM( results.broodFertile ) AS UNSIGNED ) AS broodFertile, CAST( SUM( results.broodHatched ) AS UNSIGNED ) AS broodHatched, 
                CAST( SUM( results.showCount ) AS UNSIGNED ) AS showCount, AVG( results.showScore ) AS showScore

            FROM (
				SELECT * # get district with subdistricts as we need to get results from the whole tree
				FROM (
				    SELECT @root:=:districtId, @parents:=@root AS path
				) AS init, (
				    SELECT id, parentId, name, level, moderatorId
				    FROM district 
				    WHERE moderatorId IS NOT NULL
				    ORDER BY parentId, id
				) AS sorted
				WHERE ( find_in_set(parentId, @parents) > 0 AND @parents:=CONCAT(@parents, ',', id) ) OR id=@root
            ) AS districts

            JOIN (
				SELECT # need to get wanted sections with their subsection tree, solved non recursive for max depth of 3
					g0.id AS rootId, g0.name,
					IF( ISNULL(g3.id), IF( ISNULL(g2.id), IF( ISNULL(g1.id), g0.id, g1.id ), g2.id ), g3.id ) AS id,
					IF( ISNULL(g3.id), IF( ISNULL(g2.id), IF( ISNULL(g1.id), g0.order, g1.order ), g2.order ), g3.order ) AS `order`
				FROM section AS g0
					LEFT JOIN section AS g1 ON g1.parentId = g0.id
					LEFT JOIN section AS g2 ON g2.parentId = g1.id
					LEFT JOIN section AS g3 ON g3.parentId = g2.id
				WHERE g0.id IN ( 3,11,12,13,5,6 ) # the sections to group by
				ORDER BY rootId, id 
            ) AS sections
            
            LEFT JOIN ( # now add results to district per wanted sections
                SELECT result.*, breed.sectionId AS sectionId, breed.name AS breedName, color.name AS colorName 
                FROM result 
                    LEFT JOIN breed ON breed.id=result.breedId
                    LEFT JOIN color ON color.id=result.colorId
                WHERE result.year=:year
            ) AS results ON  results.districtId=districts.id AND results.sectionId=sections.id
            
            WHERE results.breedId IS NOT NULL
            
            GROUP BY sections.rootId, results.breedName, results.colorName
            ORDER BY sections.order, results.breedName, results.colorName
        ");
/*
        $stmt = Query::prepare('
            WITH RECURSIVE districts( id, childId, name ) AS
            (
                SELECT id, id AS childId, name FROM district WHERE id=:districtId
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
 */
        return Query::selectArray( $stmt, $args );
    }
}
