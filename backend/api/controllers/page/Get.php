<?php

namespace App\controllers\page;

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
        $pageId = $args['pageId'];
        $page = queries\page\Select::execute( $pageId );
        if( ! $page ) throw new HttpNotFoundException($request, "Page not found");
        return [ 'page'=>$page ];
    }
}
