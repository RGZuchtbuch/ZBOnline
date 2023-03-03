<?php

namespace App\controllers\breeder\results;

use App\queries;
use App\controllers\Controller;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class Get extends Controller {

    public function authorized(? array $requester, array $args, array $query ) : bool {
        //$user = Queries\Authorized::get( $requester, $args['id'] );
        //return isset( $user );
        return true;
    }

    public function process(Request $request, array $args, array $query) : mixed
    {
        $breederId = $args['breederId' ];
        $breeder = queries\breeder\Select::execute( $breederId );
        $results = queries\breeder\results\Select::execute( $breederId );
        return [ 'breeder'=>$breeder, 'results'=>$results ];
    }

}