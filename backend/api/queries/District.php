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
            SELECT template.*, breed.name AS breedName, color.name AS colorName
            FROM template
            LEFT JOIN breed ON breed.id = template.breedId
            LEFT JOIN color ON color.id = template.colorId
            WHERE template.districtId=:districtId AND template.year=:year
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
        $stmt = Query::prepare('
            SELECT
                district.id AS districtId, district.name AS districtName,
                section.id AS sectionId, section.name AS sectionName,
                breed.id AS breedId, breed.name AS breedName, 
                color.id AS colorId, color.name AS colorName,
            
                template.id AS resultId, template.group AS `group`,
                template.breeders,
                template.layDames, template.layEggs, template.layWeight, 
                template.broodEggs, template.broodFertile, template.broodHatched,
                template.showCount, template.showScore
            FROM district
            
            LEFT JOIN section ON section.id = :sectionId
            LEFT JOIN breed ON breed.sectionId = section.id
            LEFT JOIN color ON color.breedId = breed.id 
            LEFT JOIN template
                ON template.breedId = breed.id AND template.colorId = color.id
                AND template.districtId = district.id
                AND template.`group`= :group
                AND template.`year` = :year
            
            WHERE district.id = :districtId
            ORDER BY breed.name, color.name       
        ');
        return Query::selectArray( $stmt, $args );
    }

}