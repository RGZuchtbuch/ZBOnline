<?php

namespace App\Queries;

class User
{
    public static function login( string $email, string $password ) : ? array {
        $user = User::getVerifiedUser( $email, $password );
        if( $user ) {
            $user['moderator'] = User::getModerator( $user['id'] ); // array
            $user['isAdmin'] = User::isAdmin( $user['id'] );
            return $user;
        }
        return null;

    }

    private static function getVerifiedUser(string $email, string $password ) : ? array {
        $args = [ 'email'=>$email ];
        $stmt = Query::prepare( 'SELECT id, name, hash, email, districtId FROM user WHERE email=:email' );
        $user = Query::select( $stmt, $args );

        if( $user && password_verify( $password, $user['hash'] ) ) {
            unset( $user['hash'] );
            return $user;
        }
        return null;
    }

    private static function getModerator( int $id ) : array {
        $args = [ 'id'=>$id ];
        $stmt = Query::prepare( 'SELECT districtId FROM moderator WHERE userId=:id' );
        $districts = Query::selectArray( $stmt, $args );
        return array_column( $districts, 'districtId' ); // only list of district id's needed
    }

    private static function isAdmin( int $id ) : bool {
        $args = [ 'id'=>$id ];
        $stmt = Query::prepare( 'SELECT userId FROM admin WHERE userId=:id' );
        return Query::select($stmt, $args ) != null;

    }
}