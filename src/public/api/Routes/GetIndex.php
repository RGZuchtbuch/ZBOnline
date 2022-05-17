<?php
namespace App\Routes;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetIndex extends Route
{

    public function isAuthorized( Request $request ) : bool {
        return true;
    }

    public function process( Request $request, Response $response, $args ) : Response {
        $this->write( $response,  "Api docs todo");
        return $response;
    }

}