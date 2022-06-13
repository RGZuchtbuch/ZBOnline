<?php

namespace App\Routes;

use App\Queries;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetDistrictBreeders extends Route {

    public function preAuthorized(?array &$requester, array &$args): bool {
        return true;
    }

    public function process(Request $request, array $args) : mixed
    {
        $districtId = $args['id'];
        return Queries\District::getBreeders( $districtId );
    }}