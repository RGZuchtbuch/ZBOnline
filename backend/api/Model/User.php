<?php

namespace App\Model;

class User
{

    public static function new( ? string $firstname, ? string $infix, ? string $lastname, ? string $password, ? string $email, int $districtId, int $clubId, string $start, ? string $end, ? string $info ) : ? int {
        return null;
    }

    public static function get( int $id ) : ? array {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            SELECT id, firstname, infix, lastname, email, districtId, clubId, start, end, info, admin
            FROM user
            WHERE id=:id
        ');
        return Query::select($stmt, $args);
    }


    public static function set( int $id,  ? string $firstname, ? string $infix, ? string $lastname, ? string $password, ? string $email, int $districtId, int $clubId, string $start, ? string $end, ? string $info ) : ? int {
        return false;
    }

    public static function del( int $id ) : bool {
        return false;
    }

    public static function authenticate( string $email, string $password ) : ? int
    {
        $args = [ 'email'=>$email ];
        $stmt = Query::prepare( '
            SELECT id, hash, email FROM user WHERE email=:email
        ' );
        $users = Query::selectArray( $stmt, $args );

        foreach( $users as $user ) {
            if ($user && password_verify($password, $user['hash'])) {
                return $user['id'];
            }
        }
        return null; // not a valid user
    }
}
