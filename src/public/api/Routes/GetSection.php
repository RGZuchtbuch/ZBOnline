<?php

namespace App\Routes;

use App\Queries;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetSection extends Route
{
    public function preAuthorized(?array &$requester, array &$args): bool
    {
        return true;
    }

    public function process(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        $section = Queries\Section::get( $id );
        $section['breeds'] = Queries\Section::getBreeds( $id );
        $this->result['section'] = $section;
        return $response;
    }
}
