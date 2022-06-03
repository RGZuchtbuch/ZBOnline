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
            SELECT * FROM section WHERE parent=:parentId ORDER BY name
        ' );
        return Query::selectArray( $stmt, $args );
    }

    public static function getTree(int $parent ) : array {
        $args = [ 'parent'=>$parent ];
        $stmt = Query::prepare( '
            WITH RECURSIVE parent AS (
                SELECT * FROM std_section WHERE id=:parent
                UNION ALL
                SELECT child.* FROM parent, std_section child
					 WHERE child.parent = parent.id 
            )
            SELECT * FROM parent ORDER BY name
        ' );
        return Query::selectArray( $stmt, $args );
    }

    public static function getBreeds( int $sectionId ) : array {
        $args = [ 'sectionId'=>$sectionId, 'subsectionId'=>$sectionId ];
        $stmt = Query::prepare( '
            SELECT std_breed.* 
            FROM std_breed
            WHERE section=:sectionId OR subsection = :subsectionId
        ' );
        return Query::selectArray( $stmt, $args );
    }

}