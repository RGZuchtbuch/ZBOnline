<?php

namespace App\controllers\district\results;

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
        $year = $args['year'];
        $district = queries\district\Select::execute( $districtId );
        $results = queries\district\results\Select::execute( $districtId );
        return [ 'district'=>$district, 'results'=>$results ]
    }
}
