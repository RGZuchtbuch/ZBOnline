<?php

namespace App\Queries;

class Moderator
{

    public static function getAll(int $districtId ) : ? array {
        $args = [ 'districtId'=>$districtId ];
        $stmt = Query::prepare( '
            SELECT * FROM moderator WHERE districtId=:districtId
        ' );
        return Query::selectArray( $stmt, $args );
    }

    public static function create( int $userId, int $districtId ) : int {
        $args = [ 'userId'=>$userId, 'districtId'=>$districtId ];
        $stmt = Query::prepare( '
            INSERT INTO moderator ( userId, districtId )
            VALUES ( :userId, :districtId )
        ');
        return Query::insert( $stmt, $args );
    }

    public static function delete( int $userId, int $districtId ) : bool {
        $args = [ 'userId'=>$userId, 'districtId'=>$districtId ];
        $stmt = Query::prepare( '
            DELETE FROM moderator
            WHERE userId=:userId AND districtId=:districtId
        ');
        return Query::delete( $stmt, $args );
    }


    public static function getDistricts( int $moderatorId ) : array {
        $args = [ 'moderatorId'=>$moderatorId ];
        $stmt = Query::prepare( '
            WITH RECURSIVE parent AS (
                SELECT district.* 
                    FROM district
                    LEFT JOIN moderator ON moderator.districtId = district.id
                    WHERE moderator.userId = :moderatorId
                UNION ALL
                SELECT child.* 
                    FROM parent, district child
                    WHERE child.parentId = parent.id 
            )
            SELECT DISTINCT * 
                FROM parent 
                ORDER BY name
        ' );
        return Query::selectTree( $stmt, $args );
    }


}
