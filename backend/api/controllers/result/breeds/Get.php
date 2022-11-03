<?php

namespace App\controllers\result\breeds;

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
        $districtId = $args['districtId'];
        $sectionId = $args[ 'sectionId' ];
        $year = $args[ 'year' ];
        $group = $args[ 'group' ];
        $breeds = queries\result\breeds\Select::execute( $districtId, $sectionId, $year, $group );

        if( ! $breeds) throw new HttpNotFoundException($request, "Breeds not found");

        return [ 'breeds'=>$breeds ];
    }
}