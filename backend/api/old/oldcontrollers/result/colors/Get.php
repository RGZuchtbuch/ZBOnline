<?php

namespace App\controllers\result\colors;

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
        $districtId = $args['districtId'];
        $breedId = $args['breedId'];
        $year = $args['year'];
        $group = $args['group'];

        $breed = queries\breed\Select::execute( $breedId );

        if( ! $breed ) throw new HttpNotFoundException($request, "Breed not found");

//        if( $Breed[ 'sectionId' ] === 5 )  {
//            $results = oldqueries\result\Breed\Select::execute( $breedId, $districtId, $year, $group );
//        } else {
            $results = queries\result\breed\colors\Select::execute( $breedId, $districtId, $year, $group );
//        }

        if( ! $results) throw new HttpNotFoundException($request, "Results not found");

        return [ 'results'=>$results ];
    }
}