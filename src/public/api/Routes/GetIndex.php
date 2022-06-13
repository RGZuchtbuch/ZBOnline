<?php
namespace App\Routes;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetIndex extends Route
{



    public function process( Request $request, $args ) : mixed
    {
        $this->write( $response,  "Api docs todo");
        return $response;
    }

}