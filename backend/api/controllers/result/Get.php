<?php

namespace App\controllers\result;

use App\queries;
use App\controllers\Controller;
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
        $result = queries\result\Select::execute( $id );

        if( ! $result) throw new HttpNotFoundException($request, "Result not found");

        return [ 'result'=>$result ];
    }
}