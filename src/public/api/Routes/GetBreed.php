<?php

namespace App\Routes;

use App\Queries;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class GetBreed extends Route
{
    public function preAuthorized(?array &$requester, array &$args): bool
    {
        return true;
    }

    public function process(Request $request, array $args) : mixed
    {
        $id = $args['id'];
        $breed = Queries\Breed::get( $id );
        if( ! $breed ) throw new HttpNotFoundException( $request, 'Breed not found' );
        $breed['colors'] = Queries\Breed::getColors( $id );
        return $breed;
    }
}
