<?php

namespace App\routes\breed;

use App\Queries;
use App\routes\Controller;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class Get extends Controller
{
    public function preAuthorized(?array &$requester, array &$args): bool
    {
        return true;
    }

    public function process(Request $request, array $args) : mixed
    {
        $id = $args['id'];
        $breed = queries\breed\Select::execute( $id );
        if( ! $breed ) throw new HttpNotFoundException( $request, 'Breed not found' );
        $breed['colors'] = queries\breed\colors\Select::execute( $id );
        return $breed;
    }
}
