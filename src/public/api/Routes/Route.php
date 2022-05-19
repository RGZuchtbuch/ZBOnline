<?php

namespace App\Routes;

use App\Utils\Token;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

abstract class Route
{
    protected ? array $requester = null;
    protected array $result = [];

    public function __invoke( Request $request, Response $response, array $args ) : Response {
        $this->requester = $this->getRequester( $request );

        if( ! $this->preAuthorized( $this->requester, $args ) ) throw new \Slim\Exception\HttpUnauthorizedException( $request, "Not Authorized for call");


        $response = $this->process($request, $response, $args);

        if( ! $this->postAuthorized( $this->requester, $args,  $this->result ) ) throw new \Slim\Exception\HttpUnauthorizedException( $request, "Not Authorized for call");

        $response->getBody()->write( json_encode( $this->result ) );
        return $response;
    }



    public function preAuthorized( ? array & $requester, array & $args ) : bool {
        return true;
    }


    public function postAuthorized( ? array & $requester, array & $args, array & $result ) : bool {
        return true;
    }

    public function process( Request $request, Response $response, array $args ) : Response {
        throw new \Slim\Exception\HttpInternalServerErrorException( $request, "Oops, this route is not processing yet" );
    }

    protected function getData( $request ) {
        return json_decode( $request->getBody(), true );
    }

    private function getRequester(Request $request ) : ? array {
        $token = $this->getToken( $request );
        if( $token ) {
            $payload = Token::decode( $token );
            if( $payload ) return $payload[ 'user' ];
        }
        return null; // not token
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
}