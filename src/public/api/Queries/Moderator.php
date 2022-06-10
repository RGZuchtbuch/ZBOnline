<?php

namespace App\Queries;

class Moderator
{

    public static function getAll(int $districtId ) : ? array {
        $args = [ 'districtId'=>$districtId ];
        $stmt = Query::prepare( '
            SELECT * FROM moderator WHERE district=:districtId
        ' );
        return Query::selectArray( $stmt, $args );
    }

    public static function create( int $userId, int $districtId ) : int {
        $args = [ 'userId'=>$userId, 'districtId'=>$districtId ];
        $stmt = Query::prepare( '
            INSERT INTO moderator ( id, district )
            VALUES ( :userId, :districtId )
        ');
        return Query::insert( $stmt, $args );
    }

    public static function delete( int $userId, int $districtId ) : bool {
        $args = [ 'userId'=>$userId, 'districtId'=>$districtId ];
        $stmt = Query::prepare( '
            DELETE FROM moderator
            WHERE id=:userId AND district=:districtId
        ');
        return Query::delete( $stmt, $args );
    }


    public static function getDistricts( int $moderatorId ) : array {
        $args = [ 'moderatorId'=>$moderatorId ];
        $stmt = Query::prepare( '
            WITH RECURSIVE parent AS (
                SELECT district.* 
                    FROM district
                    LEFT JOIN moderator ON moderator.district = district.id
                    WHERE moderator.id = :moderatorId
                UNION ALL
                SELECT child.* 
                    FROM parent, district child
                    WHERE child.parent = parent.id 
            )
            SELECT DISTINCT * 
                FROM parent 
                ORDER BY name
        ' );
        return Query::selectTree( $stmt, $args );
    }


}
/*
     protected function & toTree( int $rootId, & $array, $idName='id', $parentName='parent', $childrenName='children' ) : array {
        $values = []; // for lookup table
        foreach( $array as & $value ) { // lookup table by id
            $id = $value[ $idName ];
            $values[ $id ] = & $value;
        }
        foreach( $array as & $child ) { // build tree
            $parentId = $child[ $parentName ];
            if( isset( $parentId ) ) { // not root
                $values[$parentId][$childrenName][] = & $child;
            }
        }
        return $values[ $rootId ];
    }
 */