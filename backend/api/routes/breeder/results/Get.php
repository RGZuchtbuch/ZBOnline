<?php

namespace App\routes\breeder\results;

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
        $id = $args['breederId' ];
        $breeder = queries\breederBreeder::get( $id );
        if( ! $breeder) throw new HttpNotFoundException($request, "breeder not found");
        $breeder[ 'district' ] = queries\District::get( $breeder['districtId'] );
        $breeder[ 'club' ] = queries\District::get( $breeder['clubId'] );
        return $breeder;
    }

}