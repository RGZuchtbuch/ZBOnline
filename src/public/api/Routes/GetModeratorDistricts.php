<?php

namespace App\Routes;

use App\Queries;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetModeratorDistricts extends Route {

    public function preAuthorized( ? array & $requester, array & $args ) : bool {
        $user = Queries\Authorized::get( $requester, $args['id'] );
        return isset( $user );
    }

    public function process(Request $request, Response $response, array $args): Response
    {
        $id = $args['id' ];
        $districts = Queries\ModeratorDistricts::get( $id );
        $this->result['districts'] = $districts;
        return $response;
    }

}