<?php

namespace App\controller\user;


use App\controller\Controller;
use App\model;
use App\model\Token;
use DateTimeImmutable;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Slim\Exception\HttpNotFoundException;

class GetToken extends Controller
{


    public function authorized(): bool
    {
        return true;
    }

    public function process() : array
    {
        $email = $this->data['email'];
        $password = $this->data[ 'password' ];

        $id = model\User::authenticate( $email, $password );
        if( $id ) {
            $user = model\User::get( $id );
            $user[ 'fullname' ] = $user[ 'firstname' ].' '.($user[ 'infix' ] ? $user[ 'infix' ].' ':'').$user[ 'lastname' ];
			$user[ 'moderator' ] = array_column( model\Moderator::districts( $id ), 'id' );
            $token = Token::encode( $user );
            return [ 'token' => $token ];
        }
        throw new HttpNotFoundException( $this->request, "Invalid credentials");
    }

    public static function encode( array $data ) : string {
        $issuer = 'RG Zuchtbuch Online';
        $issued = new DateTimeImmutable();
        $expires = $issued->modify('+'.TOKEN_EXPIRE.' minutes'); // till so many minutes after now

        $payload = [
            'iss'   => $issuer,
            'iat'   => $issued->getTimestamp(),
            'nbf'   => $issued->getTimestamp(),
            'exp'   => $expires->getTimestamp(),
            'user'  => (array) $data
        ];

        return JWT::encode( $payload, TOKEN_SECRET, TOKEN_ALGORITHM );
    }

    public static function decode( string $token ) : array {
        $payload = (array)JWT::decode($token, new Key(TOKEN_SECRET, TOKEN_ALGORITHM));
        $payload['user'] = (array)$payload['user']; // convert back to 'normal' array
        return $payload;
        // may throw ExpiredException $e
    }

}