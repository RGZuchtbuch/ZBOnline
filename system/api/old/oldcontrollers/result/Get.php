<?php

namespace App\controllers\result;

use App\queries;
use App\controllers\Controller;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class Get extends Controller
{
    public function authorized(?array $requester, array $args, array $query): bool
    {
        return true;
    }

    public function process(Request $request, array $args, array $query) : mixed
    {
        $id = $args['id'];
        $result = queries\result\Select::execute( $id );

        if( ! $result) throw new HttpNotFoundException($request, "Result not found");

        return [ 'result'=>$result ];
    }
}