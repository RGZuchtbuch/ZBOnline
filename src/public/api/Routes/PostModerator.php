<?php

namespace App\Routes;

use App\Queries;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PostModerator extends Route
{
    public function preAuthorized( ? array &$requester, array &$args): bool
    {
        return true; //isset( $requester['isAdmin'] ) && $requester['isAdmin'];
    }

    public function process(Request $request, Response $response, array $args): Response
    {
        $moderator = $this->getData( $request );
        $id = Queries\Moderator::create( $moderator['user'], $moderator['district'] );
        $this->result['id'] = $id;
        return $response;
    }

}