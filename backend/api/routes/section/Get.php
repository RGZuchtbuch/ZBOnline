<?php

namespace App\routes\section;

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
        $section = queries\Section::get( $id );
        if( ! $section ) throw new HttpNotFoundException($request, "Section not found");
//        $section['breeds'] = queries\Section::getBreeds( $id );
        return $section;
    }
}
