<?php

namespace App\routes\district\results;

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
        $year = $args['year'];
        return queries\District::getResults( $districtId, $year );
    }
}
