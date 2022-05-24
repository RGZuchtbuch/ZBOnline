<?php

namespace App\Queries;

class District
{

    public static function get( int $id ) : ? array {
        $args = [ 'id'=>$id ];
        $stmt = Query::prepare( '
            SELECT * FROM district WHERE id=:id
        ' );
        return Query::get( $stmt, $args );
    }

    public static function post( int $parentId, string $name, string $short, string $coordinates ) : int {
        $args = [ 'parentId'=>$parentId, 'name'=>$name, 'short'=>$short, 'coordinates'=>$coordinates ];
        $stmt = Query::prepare( '
            INSERT INTO district ( parent, name, short, coordinates )
            VALUES ( :parentId, :name, :short, :coordinates )
        ');
        return Query::insert( $stmt, $args );
    }


    public static function getChildren( int $parentId ) : array {
        $args = [ 'parentId'=>$parentId ];
        $stmt = Query::prepare( '
            SELECT * FROM district WHERE parent=:parentId ORDER BY name
        ' );
        return Query::getArray( $stmt, $args );
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
        return Query::getArray( $stmt, $args );
    }

}