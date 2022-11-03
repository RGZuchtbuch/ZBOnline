<?php

namespace App\controllers\district;

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
        $district = queries\district\Select::execute( $districtId );
        if( ! $district ) throw new HttpNotFoundException($request, "User not found");

        $district['moderators'] = queries\district\moderators\Select::execute( $districtId );
        return [ 'district'=>$district ];
    }
}
