<?php

namespace App\Routes;

use App\Queries;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetUserResults extends Route {

    public function preAuthorized( ? array & $requester, array & $args ) : bool {
        $user = Queries\Authorized::get( $requester, $args['id'] );
        return isset( $user );
    }

    public function process(Request $request, Response $response, array $args): Response
    {
        $id = $args['id' ];
        $userResults = Queries\UserResults::get( $id );
        $this->result['results'] = $userResults;
        return $response;
    }

}