<?php

namespace App\Routes;

use App\Queries;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetSectionTree extends Route
{
    public function preAuthorized(?array &$requester, array &$args): bool
    {
        return true;
    }

    public function process(Request $request, Response $response, array $args): Response
    {
        $rootId = $args['id'];
        $sections = Queries\Section::getTree( $rootId );
        $root = $this->toTree( $sections );
        $this->result['sections'] = $root;
        return $response;
    }

}
