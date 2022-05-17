<?php
declare(strict_types=1);

namespace App\Utils;

use DateTimeImmutable;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Token
{
    private static string $secret = 'jhfvj5v6898v^&*&^T(^BB&^t97b97crvR%(&^%er86976(&V'; //TODO should not be here, set in config file that's ignored.
    private static string $alg = 'HS256';


    public static function encode( array $user ) : string {

        //$header = [ 'typ'=>'JWT', 'alg'=>Token::$alg ];


        $issuer = 'RG Zuchtbuch Online';
        $issued = new DateTimeImmutable();
        $expire = $issued->modify('+1440 minutes');

        $payload = [
            'iss'   => $issuer,
            'iat'   => $issued->getTimestamp(),
            'nbf'   => $issued->getTimestamp(),
            'exp'   => $expire->getTimestamp(),
            'user'  => $user
        ];


        return JWT::encode( $payload, Token::$secret, Token::$alg );
    }

    public static function decode( string $token ) : array {
        $payload = (array) JWT::decode( $token, new Key( Token::$secret, Token::$alg ) );
        print_r( $payload );
        return $payload;
    }



}