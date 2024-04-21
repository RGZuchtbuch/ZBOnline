<?php

namespace App\controller;

use App\Model;
use App\controller\Controller;
use Exception;
use http\Exception\InvalidArgumentException;
use PDOException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class Index
{

    public static function get( Request $request, Response $response, array $args ) : Response {
		$api = [];
		$api[] = [ 'url'=>'/standard', '?'=>'provides complete bdrg standard tree of sections, subsections, breeds and colors' ];
		$api[] = [ 'url'=>'/district', '?'=>'provides complete bdrg districts tree' ];

//        $response->getBody()->write(json_encode(['api' => $api], JSON_UNESCAPED_SLASHES));
        global $apiIndex; // to get access to the global
        $response->getBody()->write( "Todo, index of API, try ./standard" );
        return $response;
    }




}
