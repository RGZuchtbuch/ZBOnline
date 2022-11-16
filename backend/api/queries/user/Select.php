<?php

namespace App\queries\user;

use App\queries\Query;
use http\Exception\BadMessageException;

class Select extends Query
{
    public static function execute( ...$args ) : ? array {
        $vars = static::validate( ...$args );
        $args = [ 'email'=>$vars['email'] ];
        $stmt = static::prepare( 'SELECT id, name, hash, email, districtId FROM user WHERE email=:email' );
        $user = static::select( $stmt, $args );

        if( $user && password_verify( $vars['password'], $user['hash'] ) ) {
            unset( $user['hash'] );
            return $user;
        }
        return null;
    }

    private static function validate( string $email, string $password ) : array {
        if( $email && $password ) {
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}