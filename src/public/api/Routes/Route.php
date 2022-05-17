<?php

namespace App\Routes;

use App\Utils\Token;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

abstract class Route
{
    protected ? array $requester = null;

    public function __invoke( Request $request, Response $response, array $args ) : Response {
        $this->requester = $this->getPayload( $request );
        print_r( $this->requester );

        if( ! $this->isAuthorized() ) throw new \Slim\Exception\HttpUnauthorizedException( $request, "Not Authorized for call");

        return $this->process($request, $response, $args);
    }

    public function isAuthorized( Request $request ) : bool {
        return true;
    }

    public function process( Request $request, Response $response, array $args ) : Response {
        throw new \Slim\Exception\HttpInternalServerErrorException( $request, "Oops, this route is not processing yet" );
    }

    protected function getData( $request ) {
        return json_decode( $request->getBody(), true );
    }

    protected function write( Response $response, $data ) {

        $response->getBody()->write( json_encode( [ 'data'=>$data ] ) ); // room for meta data
    }

    private function getPayload( Request $request ) : ? array {
        $token = $this->getToken( $request );
        return Token::decode( $token );
    }

    protected function getToken( Request $request ) : ? string {
        $authorization = $request->getHeaderLine( 'Authorization' );

        if( ! empty( $authorization ) ) {
            $header = trim($authorization);
            if (preg_match('/Bearer\s(\S+)/', $header, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }
}