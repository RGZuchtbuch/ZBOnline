<?php

namespace App\routes\result\breed\colors;

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
        $breedId = $args['breedId'];
        $year = $args['year'];
        $group = $args['group'];

        $colors = queries\result\breed\colors\Select::execute( $breedId, $districtId, $year, $group );

        if( ! $colors) throw new HttpNotFoundException($request, "Colors not found");

        return [ 'colors'=>$colors ];
    }
}