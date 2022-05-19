<?php

namespace App\Routes;

use App\Queries;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class GetPage extends Route {

    public function preAuthorized( ? array & $requester, array & $args ) : bool {
        return true;
    }

    public function process(Request $request, Response $response, array $args): Response
    {
        $id = $args['id' ];
        $page = Queries\Page::get( $id );

        if (!$page) throw new HttpNotFoundException($request, "Unknown page for credentials");

        $this->result['page'] = $page;
        return $response;
    }

    public function postAuthorized( ? array & $requester, array & $args, array & $result ) : bool {
        return true;
    }
}
