<?php

namespace App\controllers\breeder;

use App\queries;
use App\controllers\Controller;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class Get extends Controller {

    public function preAuthorized( ? array & $requester, array & $args ) : bool {
        //$user = Queries\Authorized::get( $requester, $args['breederId'] );
        //return isset( $user );
        return true;
    }

    public function process(Request $request, array $args) : mixed
    {
        $breederId = $args['breederId' ];
        $breeder = queries\breeder\Select::execute( $breederId );
        if( ! $breeder) throw new HttpNotFoundException($request, "breeder not found");

        $breeder[ 'district' ] = queries\district\Select::execute( $breeder['districtId'] );
        $breeder[ 'club' ] = queries\club\Select::execute( $breeder['clubId'] );
        return $breeder;
    }

}