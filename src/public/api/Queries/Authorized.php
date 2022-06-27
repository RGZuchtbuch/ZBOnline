<?php

namespace App\Queries;

class Authorized {
    public static function get( ? array & $requester, int $id ) : bool {
        if( isset( $requester, $requester['id'] ) ) {
            $args = [ 'requesterId1'=>$requester['id'], 'requesterId2'=>$requester['id'], 'requesterId3'=>$requester['id'], 'requesterId4'=>$requester['id'], 'userId1'=>$id, 'userId2'=>$id ];
            $stmt = Query::prepare( '
                SELECT user.id, user.districtId, user.email
                FROM user
                LEFT JOIN moderator ON moderator.districtId = user.districtId
                LEFT JOIN admin ON admin.userId=:requesterId1
                WHERE user.id=:userId1 AND ( :userId2=:requesterId2 OR moderator.userId=:requesterId3 OR admin.userId=:requesterId4 )
            ' );
            $user = Query::select( $stmt, $args );
            return isset( $user );
        }
        return false;
    }
}