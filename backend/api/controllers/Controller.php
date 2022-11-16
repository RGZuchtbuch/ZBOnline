<?php

namespace App\controllers;

use App\Config;
use App\utils\Token;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

//

abstract class Controller
{
    public static ? array $requester = null;

    public function __invoke( Request $request, Response $response, array $args ) : Response {
        Controller::$requester = $this->getRequester( $request );
        if( ! Controller::$requester ) {
            Controller::$requester = ['id'=>1 ]; //TODO risky !!!!!!!!!!!!!!!!!!!!
        }

        if( ! $this->preAuthorized( Controller::$requester, $args ) ) throw new \Slim\Exception\HttpUnauthorizedException( $request, "Not Authorized for call");

        $data = $this->process($request, $args);

//        if( ! $this->postAuthorized( $this->requester, $args,  $this->template ) ) throw new \Slim\Exception\HttpUnauthorizedException( $request, "Not Authorized for call");

        $response->getBody()->write( json_encode( $data ) );
        return $response;
    }



    public function preAuthorized( ? array & $requester, array & $args ) : bool {
        return true;
    }


    public function postAuthorized( ? array & $requester, array & $args, array & $result ) : bool {
        return true;
    }

    public function process( Request $request, array $args ) : mixed {
        throw new \Slim\Exception\HttpInternalServerErrorException( $request, "Oops, this route is not processing yet" );
    }

    protected function getData( $request ) {
        return json_decode( $request->getBody(), true );
    }

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

    protected function & toTree( int $rootId, & $array, $idName='id', $parentName='parent', $childrenName='children' ) : array {
        $values = []; // for lookup table
        foreach( $array as & $value ) { // lookup table by id
            $id = $value[ $idName ];
            $values[ $id ] = & $value;
        }
        foreach( $array as & $child ) { // build tree
            $parentId = $child[ $parentName ];
            if( isset( $parentId ) ) { // not root
                $values[$parentId][$childrenName][] = & $child;
            }
        }
        return $values[ $rootId ];
    }


    private static function decode( string $token ) : array {
        $payload = (array) JWT::decode( $token, new Key( Config::TOKEN_SECRET, Config::TOKEN_ALGORITHM ) );
        $payload['user'] = (array) $payload['user']; // convert back to 'normal' array
        return $payload;
    }
}