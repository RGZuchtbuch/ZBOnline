<?php

namespace App\Routes;

use App\Queries;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetColor extends Route
{
    public function preAuthorized(?array &$requester, array &$args): bool
    {
        return true;
    }

    public function process(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        $color = Queries\Color::get( $id );
        $this->result['color'] = $color;
        return $response;
    }
}
