<?php

namespace App\controllers\breeder\results;

use App\queries;
use App\controllers\Controller;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class Get extends Controller {

    public function preAuthorized( ? array & $requester, array & $args ) : bool {
        //$user = Queries\Authorized::get( $requester, $args['id'] );
        //return isset( $user );
        return true;
    }

    public function process(Request $request, array $args) : mixed
    {
        $breederId = $args['breederId' ];
        $breeder = queries\breeder\Select::execute( $breederId );
        $results = queries\breeder\results\Select::execute( $breederId );
        if( ! $results) throw new HttpNotFoundException($request, "results for breeder not found");

        return [ 'breeder'=>$breeder, 'results'=>$results ];
    }

}