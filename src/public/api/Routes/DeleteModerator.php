<?php

namespace App\Routes;

use App\Queries;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DeleteModerator extends Route
{
    public function preAuthorized( ? array &$requester, array &$args): bool
    {
        return true; //isset( $requester['isAdmin'] ) && $requester['isAdmin'];
    }

    public function process(Request $request, array $args) : mixed
    {
        $moderator = $this->getData( $request );
        return Queries\Moderator::delete( $moderator[ 'user' ], $moderator[ 'district' ] );
    }

}