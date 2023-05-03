<?php

namespace App\Controller;

use App\Controller\User\Token;
use App\Query;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

//$controller = null; // not used...

abstract class Controller
{
    protected Request $request;
    protected Response $response;
    public ? array $requester;
    public array $args;
    public ? array $data;
    public array $query;

    // Relays call to specific controller with auth step.
    public function __invoke( Request $request, Response $response, array $args ) : Response {
        global $controller;
        $controller = $this;
        $this->requester = $this->getRequester( $request ); // null or valid requester
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;
        $this->data = json_decode( $request->getBody(), true );
        $this->query = $request->getQueryParams();
        $authorized = $this->authorized();

        // log
        Query\Log::new(
            $request->getMethod(),
            $request->getUri()->getPath(),
            $request->getUri()->getQuery(),
            $request->getBody(),
            $this->requester ? $this->requester['id'] : null
        );

        // start processing
        if( $authorized ) {
            $json = $this->getCache();
            if( $json == NUll ) {
                $data = $this->process();
                $json = json_encode( $data, JSON_UNESCAPED_SLASHES );
                $this->setCache( $json );
            }
            $response->getBody()->write( $json );
            return $response;
        } else {
            throw new \Slim\Exception\HttpUnauthorizedException( $request, 'Not Authorized');
        }
    }

    public abstract function authorized() : bool;
    public function getCache() : ? string {
        return null;
    }
    public function setCache( ? string $json ) : ? bool {
        return null;
    }
    public abstract function process() : array;


    // get requester data from token
    private function getRequester( Request $request ) : ? array {
        $token = static::getToken( $request );
        if( $token ) {
            $payload = Token::decode( $token );
            if( $payload ) {
                return $payload[ 'user' ];
            }
        }
        return null; // no credentials
    }

    // get token from request
    private static function getToken( Request $request ) : ? string {
        $authorization = $request->getHeaderLine( 'Authorization' );
        if( $authorization && !empty( $authorization ) ) {
            $header = trim($authorization);
            if (preg_match('/Bearer\s(\S+)/', $header, $matches )) { // get token part of header
                return $matches[1];
            }
        }
        return null;
    }



    public static function tree( array $rows ) : ? array // uses id and parentId for hierarchy
    {
        $root = null;
        $nodes = [];
        foreach( $rows as & $row ) {
            $row[ 'children' ] = []; // all nodes can have children
            $nodes[ $row['id'] ] = & $row;
        }
        foreach( $rows as & $child ) {
            $parentId = & $child[ 'parentId' ];
            if( $parentId && isset( $nodes[ $parentId ] ) ) { //has and exists in array
                $parent = & $nodes[ $parentId ];
                $parent[ 'children' ][] = & $child;
            } else {
                $root = & $child;
            }
        }
        return $root;
    }
}