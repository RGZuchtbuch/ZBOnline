<?php

namespace App\Queries;

class User
{
    public static function get( int $id ) : ? array {
        $args = [ 'userId'=>$id ];
        $stmt = Query::prepare( '
            SELECT user.id, name, email, user.district 
            FROM user
            WHERE user.id=:userId
        ' );
        return Query::select( $stmt, $args );
    }

    public static function getAll() : ? array {
        $stmt = Query::prepare( '
            SELECT user.id, name, email, user.district 
            FROM user
        ' );
        return Query::selectArray( $stmt );
    }


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
        $stmt = Query::prepare( 'SELECT id, name, hash, email, district FROM user WHERE email=:email' );
        $user = Query::select( $stmt, $args );

        if( $user && password_verify( $password, $user['hash'] ) ) {
            unset( $user['hash'] );
            return $user;
        }
        return null;
    }

    private static function getModerator( int $id ) : array {
        $args = [ 'id'=>$id ];
        $stmt = Query::prepare( 'SELECT district FROM moderator WHERE id=:id' );
        $districts = Query::selectArray( $stmt, $args );
        return array_column( $districts, 'district' ); // only list of district id's needed
    }

    private static function isAdmin( int $id ) : bool {
        $args = [ 'id'=>$id ];
        $stmt = Query::prepare( 'SELECT id FROM admin WHERE id=:id' );
        return Query::select($stmt, $args ) != null;

    }
}