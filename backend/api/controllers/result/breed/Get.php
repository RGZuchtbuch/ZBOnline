<?php

namespace App\controllers\result\breed;

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
        $breedId = $args['breedId'];
        $year = $args['year'];
        $group = $args['group'];

        $breed = queries\breed\Select::execute( $breedId );

        if( ! $breed ) throw new HttpNotFoundException($request, "Breed not found");

        if( $breed[ 'sectionId' ] === 5 )  {
            $results = queries\result\breed\Select::execute( $breedId, $districtId, $year, $group );
        } else {
            $results = queries\result\breed\colors\Select::execute( $breedId, $districtId, $year, $group );
        }

        if( ! $results) throw new HttpNotFoundException($request, "Results not found");

        return [ 'results'=>$results ];
    }
}