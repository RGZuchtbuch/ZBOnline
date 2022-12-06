<?php

namespace App\controllers\user\credentials;

use App\queries;
use App\controllers\Controller;
use App\utils\Token;
use App\Config;
use DateTimeImmutable;
use Firebase\JWT\JWT;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;

class Post extends Controller {

    public function preAuthorized( ? array & $requester, array & $args ) : bool {
        return true;
    }

    public function process(Request $request, array $args) : mixed
    {
        $data = $this->getData( $request );
        $user = queries\user\authenticate\Select::execute( $data['email'], $data['password'] );

        if( ! $user ) throw new HttpUnauthorizedException( $request, "Unknown user for credentials" );

        $user[ 'moderating' ] = queries\user\moderating\Select::execute( $user['id'] ); // array
        $user[ 'admin' ] = queries\user\admin\Select::execute( $user['id'] ); // bool

        return [ 'token'=>static::encode( $user ) ];
    }

    public static function encode( array $user ) : string {

        //$header = [ 'typ'=>'JWT', 'alg'=>Token::$alg ];


        $issuer = 'RG Zuchtbuch Online';
        $issued = new DateTimeImmutable();
        $expires = $issued->modify('+1440 minutes');

        $payload = [
            'iss'   => $issuer,
            'iat'   => $issued->getTimestamp(),
//            'nbf'   => $issued->getTimestamp(),
            'exp'   => $expires->getTimestamp(),
            'user'  => (array) $user
        ];

        return JWT::encode( $payload, Config::TOKEN_SECRET, Config::TOKEN_ALGORITHM );
    }


}