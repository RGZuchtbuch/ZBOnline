<?php

namespace App\routes\color;

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
        $color = queries\Color::get( $id );
        if( ! $color) throw new HttpNotFoundException($request, "User not found");
        return $color;
    }
}
