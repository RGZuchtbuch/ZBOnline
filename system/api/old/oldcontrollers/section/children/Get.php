<?php

namespace App\controllers\section\children;

use App\queries;
use App\controllers\Controller;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class Get extends Controller
{
    public function authorized(?array $requester, array $args, array $query): bool
    {
        return true;
    }

    public function process(Request $request, array $args, array $query) : mixed
    {
        $sectionId = $args['sectionId'];
        return [ 'sections'=>queries\section\children\Select::execute( $sectionId ) ];
    }

}
