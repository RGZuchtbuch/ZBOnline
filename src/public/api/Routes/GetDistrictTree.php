<?php

namespace App\Routes;

use App\Queries;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetDistrictTree extends Route
{
    public function preAuthorized(?array &$requester, array &$args): bool
    {
        return true;
    }

    public function process(Request $request, Response $response, array $args): Response
    {
        $rootId = $args['id'];
        $districts = Queries\District::getTree( $rootId );
        $root = $this->toTree( $rootId, $districts );
        $this->result['district'] = $root;
        return $response;
    }

}
