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
        $user = Queries\User::get( $id );
        if( ! $user) throw new HttpNotFoundException($request, "User not found");
        $user[ 'district' ] = Queries\District::get( $user['district'] );
        $user[ 'club' ] = Queries\District::get( $user['club'] );
        return $user;
    }

}