<?php

namespace App\Controller;

use App\Model;
use App\Controller\Controller;
use Exception;
use http\Exception\InvalidArgumentException;
use PDOException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class Index
{

    public function __invoke( Request $request, Response $response, array $args ) : Response {
        $response->getBody()->write( "Here the index of the RGZuchtbuch API will be listed" );
        return $response;
    }

}
