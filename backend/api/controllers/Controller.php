<?php

namespace App\controllers;

use App\Config;
//use App\utils\Token;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
//use Slim\Exception\HttpUnauthorizedException;

//

abstract class Controller
{
    public static ? array $requester = null;

    // Relays call to specific controller with auth step.
    public function __invoke( Request $request, Response $response, array $args ) : Response {
        Controller::$requester = $this->getRequester( $request ); // null or valid requester
        $query = $request->getQueryParams();

        if( $this->authorized( Controller::$requester, $args, $query ) ) {
            $data = $this->process( $request, $args, $query );

            $response->getBody()->write(json_encode($data));
            return $response;
        } else {
            throw new \Slim\Exception\HttpUnauthorizedException( $request, 'Not Authorized');
        }
    }



    public function authorized( ? array $requester, array $args, array $query ) : bool { // TODO make abstract to enforce implementation
        return true; // TODO then return false;
    }


    public function postAuthorized( ? array $requester, array $args, array $result ) : bool { // TODO, may not be needed
        return true;
    }

    public function process( Request $request, array $args, array $query ) : mixed { // must be overriden
        throw new \Slim\Exception\HttpInternalServerErrorException( $request, "Oops, this route is not processing yet" );
    }

    protected function getData( $request ) {
        return json_decode( $request->getBody(), true );
    }

    // get requester data from token
    private function getRequester(Request $request ) : ? array {
        global $requester;
        $token = $this->getToken( $request );
        if( $token ) {
            $payload = static::decode( $token );
            if( $payload ) {
                return $payload[ 'user' ];
            }
        }
        return null; // not credentials
    }

    // get token from request
    protected function getToken( Request $request ) : ? string {
        $authorization = $request->getHeaderLine( 'Authorization' );

        if( $authorization && !empty( $authorization ) ) {
            $header = trim($authorization);
            if (preg_match('/Bearer\s(\S+)/', $header, $matches )) {
                return $matches[1];
            }
        }
        return null;
    }

    private static function decode( string $token ) : array {
        try {
            $payload = (array)JWT::decode($token, new Key(Config::TOKEN_SECRET, Config::TOKEN_ALGORITHM));
            $payload['user'] = (array)$payload['user']; // convert back to 'normal' array
            return $payload;
        } catch( ExpiredException $e ) {
            throw new SignatureInvalidException( $e->getMessage() );
//            throw new HttpUnauthorizedException( $e->getMessage() );
        }
    }
}