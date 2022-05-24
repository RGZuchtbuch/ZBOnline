<?php

namespace App\Routes;

use App\Queries;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetDistrict extends Route
{
    public function preAuthorized(?array &$requester, array &$args): bool
    {
        return true;
    }

    public function process(Request $request, Response $response, array $args): Response
    {
        $districtId = $args['id'];
        $district = Queries\District::get( $districtId );
        if( isset( $district ) ) {
            $district['children'] = Queries\District::getChildren( $districtId );
        }
        $this->result['district'] = $district;
        return $response;
    }

}
