<?php

namespace App\routes\district;

use App\Queries;
use App\routes\Controller;
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
        $districtId = $args['id'];
        $district = queries\District::get( $districtId );
        if( ! $district ) throw new HttpNotFoundException($request, "User not found");

        $district['moderators'] = queries\District::getModerators( $districtId );
        return [ 'district'=>$district ];
    }
}
