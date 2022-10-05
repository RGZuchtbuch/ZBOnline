<?php

namespace App\routes\section\children;

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
        $parentId = $args['id'];
        return [ 'sections'=>queries\Section::getChildren( $parentId ) ];
    }

}
