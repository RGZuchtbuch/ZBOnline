<?php

namespace App\Routes;

use App\Queries;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PostDistrict extends Route
{
    public function preAuthorized( ? array &$requester, array &$args): bool
    {
        print_r( $requester );
        return true; //isset( $requester['isAdmin'] ) && $requester['isAdmin'];
    }

    public function process(Request $request, Response $response, array $args): Response
    {
        $district = $this->getData( $request );
        $id = Queries\District::post( $district['parent'], $district['name'], $district['short'], $district['coordinates'] );
        $this->result['id'] = $id;
        return $response;
    }

}