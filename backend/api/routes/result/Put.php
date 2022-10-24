<?php

namespace App\routes\result;

use App\Queries;
use App\routes\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Put extends Controller
{
    public function preAuthorized( ? array &$requester, array &$args): bool
    {
        return true; //isset( $requester['isAdmin'] ) && $requester['isAdmin'];
    }

    public function process(Request $request, array $args) : bool
    {
        $pair = $this->getData( $request );
        return Queries\Report::update( $pair );
    }


}