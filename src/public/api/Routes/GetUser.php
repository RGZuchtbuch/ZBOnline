<?php

namespace App\Routes;

use App\Queries;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetUser extends Route {

    public function preAuthorized( ? array & $requester, array & $args ) : bool {
        $user = Queries\Authorized::get( $requester, $args['id'] );
        return isset( $user );
    }

    public function process(Request $request, Response $response, array $args): Response
    {
        $id = $args['id' ];

        $user = Queries\User::get( $id );

        $this->result['user'] = $user;
        return $response;
    }

}