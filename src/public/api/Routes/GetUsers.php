<?php

namespace App\Routes;

use App\Queries;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetUsers extends Route {

    public function preAuthorized( ? array & $requester, array & $args ) : bool {

        return true; // isset( $requester ) && $requester[ 'isAdmin' ];
    }

    public function process(Request $request, Response $response, array $args): Response
    {
        $users = Queries\User::getAll();

        $this->result['users'] = $users;
        return $response;
    }

}