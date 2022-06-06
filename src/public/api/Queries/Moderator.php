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
}