<?php

namespace App\Controller\User;

use App\Config;
use App\Model;
use App\Controller\Controller;
use DateTimeImmutable;
use Firebase\JWT\JWT;
use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;

class Token extends Controller
{


    public function authorized(): bool
    {
        return true;
    }

    public function process() : array
    {
        $email = $this->data['email'];
        $password = $this->data[ 'password' ];

        $id = Model\User::authenticate( $email, $password );
        if( $id ) {
            $user = Model\User::get( $id );
            $user[ 'fullname' ] = $user[ 'firstname' ].' '.($user[ 'infix' ] ? $user[ 'infix' ].' ':'').$user[ 'lastname' ];
            $user[ 'moderator' ] = array_column( Model\Moderator::districts( $id ), 'districtId' );
            $token = $this->encode( $user );
            return [ 'token' => $token ];
        }
        throw new HttpNotFoundException( $this->request, "Invalid credentials");
    }

    private function encode( array $user ) : string {
        $issuer = 'RG Zuchtbuch Online';
        $issued = new DateTimeImmutable();
        $expires = $issued->modify('+'.Config::TOKEN_EXPIRE.' minutes');

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