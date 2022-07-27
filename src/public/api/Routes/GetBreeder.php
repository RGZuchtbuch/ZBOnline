<?php

namespace App\Routes;

use App\Queries;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class GetBreeder extends Route {

    public function preAuthorized( ? array & $requester, array & $args ) : bool {
        $user = Queries\Authorized::get( $requester, $args['id'] );
        return isset( $user );
    }

    public function process(Request $request, array $args) : mixed
    {
        $id = $args['id' ];
        $breeder = Queries\Breeder::get( $id );
        if( ! $breeder) throw new HttpNotFoundException($request, "breeder not found");
        $breeder[ 'district' ] = Queries\District::get( $breeder['districtId'] );
        $breeder[ 'club' ] = Queries\District::get( $breeder['clubId'] );
        return $breeder;
    }

}