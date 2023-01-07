<?php

namespace App\controllers\map\count;

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
        $year = $args[ 'year' ];
        $sectionId = $args[ 'sectionId' ];
        $districts = queries\map\count\Select::execute( $year, $sectionId );

        return [ 'districts'=>$districts ];
    }
}