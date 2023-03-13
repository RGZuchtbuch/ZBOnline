<?php

namespace App\controllers\breeder\reports;

use App\queries;
use App\controllers\Controller;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class Get extends Controller {

    public function authorized(? array $requester, array $args, array $query ) : bool {
        return queries\breeder\Authorized::execute( $requester['id'], $args[ 'breederId' ] );
    }

    public function process(Request $request, array $args, array $query) : mixed
    {
        $breederId = $args['breederId' ];
        $breeder = queries\breeder\Select::execute( $breederId );
        $reports = queries\breeder\reports\Select::execute( $breederId );
        return [ 'breeder'=>$breeder, 'reports'=>$reports ];
    }

}


