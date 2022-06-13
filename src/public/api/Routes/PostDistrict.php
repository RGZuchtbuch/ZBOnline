<?php

namespace App\Routes;

use App\Queries;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PostDistrict extends Route
{
    public function preAuthorized( ? array &$requester, array &$args): bool
    {
        return true; //isset( $requester['isAdmin'] ) && $requester['isAdmin'];
    }

    public function process(Request $request, array $args) : mixed
    {
        $district = $this->getData( $request );
        return Queries\District::create( $district['parent'], $district['name'], $district['fullname'], $district['short'], $district['coordinates'] );
    }

}