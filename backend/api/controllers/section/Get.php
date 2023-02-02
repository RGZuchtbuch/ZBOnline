<?php

namespace App\controllers\section;

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
        $sectionId = $args['sectionId'];
        $section = queries\section\Select::execute( $sectionId );
        if( ! $section ) throw new HttpNotFoundException($request, "Section not found");
        return [ 'section'=>$section ];
    }
}
