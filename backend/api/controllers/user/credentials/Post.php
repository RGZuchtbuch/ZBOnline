<?php

namespace App\controllers\user\credentials;

use App\queries;
use App\controllers\Controller;
use App\Config;
use DateTimeImmutable;
use Firebase\JWT\JWT;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpUnauthorizedException;

class Post extends Controller {

    public function authorized(? array $requester, array $args, array $query ) : bool {
        return true;
    }

    public function process(Request $request, array $args, array $query) : mixed
    {
        $data = $this->getData( $request );
        $user = queries\user\authenticate\Select::execute( $data['email'], $data['password'] );

        if( ! $user ) throw new HttpUnauthorizedException( $request, "Unknown user for credentials" );

        $user[ 'moderator' ] = queries\user\moderator\Select::execute( $user['id'] ); // array
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
            'nbf'   => $issued->getTimestamp(),
            'exp'   => $expires->getTimestamp(),
            'user'  => (array) $user
        ];

        return JWT::encode( $payload, Config::TOKEN_SECRET, Config::TOKEN_ALGORITHM );
    }


}