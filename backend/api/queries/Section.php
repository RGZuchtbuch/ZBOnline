<?php

namespace App\Queries;

class Section
{

    public static function get( int $id ) : ? array {
        $args = [ 'id'=>$id ];
        $stmt = Query::prepare( '
            SELECT * FROM std_section WHERE id=:id
        ' );
        return Query::select( $stmt, $args );
    }

    public static function getChildren( int $parentId ) : array {
        $args = [ 'parentId'=>$parentId ];
        $stmt = Query::prepare( '
            SELECT * FROM section WHERE parentId=:parentId ORDER BY name
        ' );
        return Query::selectArray( $stmt, $args );
    }

    public static function getTree(int $parentId ) : array {
        $args = [ 'parentId'=>$parentId ];
        $stmt = Query::prepare( '
            WITH RECURSIVE parent AS (
                SELECT * FROM std_section WHERE id=:parentId ORDER BY name
                UNION ALL
                SELECT child.* FROM parent, std_section child
			    WHERE child.parentId = parent.id ORDER BY child.name
            )
            SELECT * FROM parent
        ' );
        return Query::selectTree( $stmt, $args );
    }

    public static function getBreeds( int $sectionId ) : array {
        $args = [ 'sectionId'=>$sectionId, 'subsectionId'=>$sectionId ];
        $stmt = Query::prepare( '
            SELECT breed.* 
            FROM breed
            WHERE sectionId=:sectionId OR subsectionId = :subsectionId
            ORDER BY name
        ' );
        return Query::selectArray( $stmt, $args );
    }

}