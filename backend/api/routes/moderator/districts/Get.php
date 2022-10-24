<?php

namespace App\routes\moderator\districts;

use App\Queries;
use App\routes\Controller;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class Get extends Controller {

    public function preAuthorized( ? array & $requester, array & $args ) : bool {
        //$user = queries\Authorized::get( $requester, $args['id'] );
        return true;
    }

    public function process(Request $request, array $args) : mixed
    {
        $moderatorId = $args['moderatorId' ];
        return Queries\Moderator::getDistricts( $moderatorId );
    }

}