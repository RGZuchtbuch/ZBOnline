<?php
namespace App\middleware;

use App\Utils\Token;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;

class Authenticate
{
    /**
     * Example middleware invokable class
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $next)
    {
        //$user = Token::decode( $request->getHeader( )[0] );
        if( $request->hasHeader( 'Authorization' ) ) {
            print_r( $request->getHeader( 'Authorization') );
        } else {
            throw new HttpUnauthorizedException( $request, "Unknown user for credentials" );
        }
        print_r( $request->getHeader( 'Authorization') );
//        $response->getBody()->write('BEFORE');
//        $response = $next($request, $response);
//        $response->getBody()->write('AFTER');

        return null;//$response;
    }
}