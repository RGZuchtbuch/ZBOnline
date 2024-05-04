<?php

namespace App\model;

class User extends Query
{
/*
    public static function new( ? string $firstname, ? string $infix, ? string $lastname, ? string $email, int $districtId, int $clubId, string $start, ? string $end, ? string $info ) : ? int {
        // handled by breeder
        return null;
    }
*/

    public static function get( int $id ) : ? array {
        // TODO does breeder not handle this, needs check
        $args = get_defined_vars();
        $stmt = Query::prepare('
            SELECT id, firstname, infix, lastname, email, districtId, clubId, start, end, info, admin
            FROM user
            WHERE id=:id
        ');
        return Query::select($stmt, $args);
    }

    public static function getByEmail( string $email ) : ? array {
        // TODO does breeder not handle this, needs check
        $args = get_defined_vars();
        $stmt = Query::prepare('
            SELECT id, firstname, infix, lastname, email, districtId, clubId, start, end, info, admin
            FROM user
            WHERE email=:email
        ');
        return Query::select($stmt, $args);
    }
/*
    public static function new( ? string $firstname, ? string $infix, ? string $lastname, ? string $email, int districtId, int $clubId, string $start, ? string $end, ? string $info, int $modifierId ) : bool {
 */

/*
    public static function set( int $id,  ? string $firstname, ? string $infix, ? string $lastname, ? string $email, int $clubId, string $start, ? string $end, ? string $info, int $modifierId ) : bool {
        // handled by breeder
        return null;
    }
*/

/* Breeder should not delete but remove sensitive info.
    public static function del( int $id ) : bool {
        return false;
    }
*/
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

    public static function setPassword( string $email, string $password ) : bool {
        $hash = password_hash( $password, PASSWORD_DEFAULT );
        $args = [ 'email'=>$email, 'hash'=>$hash ];
        $stmt = Query::prepare('
            UPDATE user
            SET hash=:hash, modifierId=0
            WHERE email=:email
        ');
        return Query::update($stmt, $args);
    }
}
