<?php

namespace App\Routes;

use App\Queries;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PutDistrict extends Route
{
    public function preAuthorized( ? array &$requester, array &$args): bool
    {
        print_r( $requester );
        return true; //isset( $requester['isAdmin'] ) && $requester['isAdmin'];
    }

    public function process(Request $request, array $args) : mixed
    {
        $district = $this->getData( $request );
        return Queries\District::update( $district['id'], $district['name'], $district['fullname'], $district['short'], $district['coordinates'] );
    }

}