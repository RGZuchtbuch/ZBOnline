<?php

namespace App\controllers\breed\colors;

use App\queries;
use App\controllers\Controller;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class Get extends Controller
{
    public function preAuthorized(?array &$requester, array &$args): bool {
        return true;
    }

    public function process(Request $request, array $args) : mixed {
        $colors = queries\breed\colors\Select::execute( $args['id'] );
        return [ 'colors'=>$colors ];
    }
}
