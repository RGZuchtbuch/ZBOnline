<?php

namespace App\Queries;

class District
{

    public static function get(int $id ) : ? array {
        $args = [ 'id'=>$id ];
        $stmt = Query::prepare( '
            SELECT * FROM district WHERE id=:id
        ' );
        return Query::select( $stmt, $args );
    }

    public static function create( int $parentId, string $name, string $fullname, string $short, string $coordinates ) : int {
        $args = [ 'parentId'=>$parentId, 'name'=>$name, 'fullname'=>$fullname, 'short'=>$short, 'coordinates'=>$coordinates ];
        $stmt = Query::prepare( '
            INSERT INTO district ( parentId, name, fullname, short, coordinates )
            VALUES ( :parentId, :name, :fullname, :short, :coordinates )
        ');
        return Query::insert( $stmt, $args );
    }

    public static function update( int $id, string $name, string $fullname, string $short, string $coordinates ) : int {
        $args = [ 'id'=>$id, 'name'=>$name, 'fullname'=>$fullname, 'short'=>$short, 'coordinates'=>$coordinates ];
        $stmt = Query::prepare( '
            UPDATE district 
            SET name=:name, fullname=:fullname, short=:short, coordinates=:coordinates
            WHERE id=:id            
        ');
        return Query::update( $stmt, $args );
    }

    public static function getBreeders( int $districtId ) : array {
        $args = [ 'districtId'=>$districtId ];
        $stmt = Query::prepare( '
            SELECT user.id, user.name, user.districtId FROM user
            WHERE user.districtId = :districtId
            ORDER BY name
        ' );
        return Query::selectArray( $stmt, $args );
    }

    public static function getChildren( int $parentId ) : array {
        $args = [ 'parentId'=>$parentId ];
        $stmt = Query::prepare( '
            SELECT * FROM district WHERE parentId=:parentId ORDER BY name
        ' );

        return Query::selectArray( $stmt, $args );
    }


    public static function getModerators( int $districtId ) : array {
        $args = [ 'districtId'=>$districtId ];
        $stmt = Query::prepare( '
            SELECT user.id, user.name 
            FROM moderator
            LEFT JOIN user ON user.id = moderator.userId
            WHERE moderator.districtId=:districtId
            ORDER BY name
        ' );
        return Query::selectArray( $stmt, $args );
    }

    public static function getResults( int $districtId, int $year ) : array {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            SELECT result.*, breed.name AS breedName, color.name AS colorName
            FROM result
            LEFT JOIN breed ON breed.id = result.breedId
            LEFT JOIN color ON color.id = result.colorId
            WHERE result.districtId=:districtId AND result.year=:year
            ORDER BY year, `group`, sectionId, breed.name, color.name
        ' );
        return Query::selectArray( $stmt, $args );
    }

    public static function getTree(int $parentId ) : array {
        $args = [ 'parentId'=>$parentId ];
        $stmt = Query::prepare( '
            WITH RECURSIVE parent AS (
                SELECT * FROM district WHERE id=:parentId
                UNION ALL
                SELECT child.* 
                FROM parent, district child
				WHERE child.parentId = parent.id 
            )
            SELECT * FROM parent ORDER BY name
        ' );
        return Query::selectTree( $stmt, $args );
    }

    public static function getSectionFullResults( int $districtId, int $sectionId, int $year, string $group ) : array {
        $args = get_defined_vars();
        if( $sectionId === 5 ) {
            $stmt = Query::prepare('
                SELECT 
                    breed.sectionId, breed.id AS breedId, breed.name AS breedName,
                    result.breeders, 
                    result.broodEggs, result.broodHatched,
                    result.showCount, result.showScore
                FROM breed
            
                LEFT JOIN result
                    ON result.breedId = breed.id
                    AND result.districtId = :districtId
                    AND result.`group`= :group
                    AND result.`year` = :year
                
                WHERE breed.sectionId=:sectionId
                ORDER BY breed.id
            ');
        } else {
            $stmt = Query::prepare('
                SELECT 
                    breed.sectionId, breed.id AS breedId, breed.name AS breedName, color.id AS colorId, color.name AS colorName,
                    result.id AS resultId, 
                    result.breeders,
                    result.layDames, result.layEggs, result.layWeight, 
                    result.broodEggs, result.broodFertile, result.broodHatched,
                    result.showCount, result.showScore
                FROM breed
                
                LEFT JOIN color ON color.breedId = breed.id 
                LEFT JOIN result
                    ON result.breedId = breed.id AND result.colorId = color.id
                    AND result.districtId = :districtId
                    AND result.`group`= :group
                    AND result.`year` = :year
                
                WHERE breed.sectionId = :sectionId
                ORDER BY breed.name, color.name        
            ');
        }
        return Query::selectArray( $stmt, $args );
    }

}