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
            INSERT INTO district ( parent, name, fullname, short, coordinates )
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
            SELECT user.id, user.name, user.district FROM user
            WHERE user.district = :districtId
            ORDER BY name
        ' );
        return Query::selectArray( $stmt, $args );
    }

    public static function getChildren( int $parentId ) : array {
        $args = [ 'parentId'=>$parentId ];
        $stmt = Query::prepare( '
            SELECT * FROM district WHERE parent=:parentId ORDER BY name
        ' );
        return Query::selectArray( $stmt, $args );
    }

    public static function getModerators( int $districtId ) : array {
        $args = [ 'districtId'=>$districtId ];
        $stmt = Query::prepare( '
            SELECT user.id, user.name FROM moderator
            LEFT JOIN user ON user.id = moderator.id
            WHERE moderator.district=:districtId
            ORDER BY name
        ' );
        return Query::selectArray( $stmt, $args );
    }

    public static function getTree(int $parent ) : array {
        $args = [ 'parent'=>$parent ];
        $stmt = Query::prepare( '
            WITH RECURSIVE parent AS (
                SELECT * FROM district WHERE id=:parent
                UNION ALL
                SELECT child.* FROM parent, district child
					 WHERE child.parent = parent.id 
            )
            SELECT * FROM parent ORDER BY name
        ' );
        return Query::selectTree( $stmt, $args );
    }

}