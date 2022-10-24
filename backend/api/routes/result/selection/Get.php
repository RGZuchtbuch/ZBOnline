<?php

namespace App\routes\result\selection;

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
        $districtId = $args['districtId'];
        $sectionId = $args[ 'sectionId' ];
        $year = $args[ 'year' ];
        $group = $args[ 'group' ];
        $results = queries\result\selection\Select::execute( $districtId, $sectionId, $year, $group );

        if( ! $results) throw new HttpNotFoundException($request, "Results not found");

        return [ 'results'=>$results ];
    }
}