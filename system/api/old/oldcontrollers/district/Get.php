<?php

namespace App\controllers\district;

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
        $district = queries\district\Select::execute( $districtId );
        if( ! $district ) throw new HttpNotFoundException($request, "User not found");

        $district['moderators'] = queries\district\moderators\Select::execute( $districtId );
        return [ 'district'=>$district ];
    }
}
