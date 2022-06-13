<?php

namespace App\Routes;

use App\Queries;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class GetSection extends Route
{
    public function preAuthorized(?array &$requester, array &$args): bool
    {
        return true;
    }

    public function process(Request $request, array $args) : mixed
    {
        $id = $args['id'];
        $section = Queries\Section::get( $id );
        if( ! $section ) throw new HttpNotFoundException($request, "Section not found");
        $section['breeds'] = Queries\Section::getBreeds( $id );
        return $section;
    }
}
