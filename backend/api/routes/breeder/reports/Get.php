<?php

namespace App\routes\breeder\reports;

use App\Queries;
use App\routes\Controller;
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
        $reports = queries\breeder\reports\select::execute( $breederId );
        return [ 'reports'=>$reports ];
    }

}