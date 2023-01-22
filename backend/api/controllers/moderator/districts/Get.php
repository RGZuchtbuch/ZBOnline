<?php

namespace App\controllers\moderator\districts;

use App\queries;
use App\controllers\Controller;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class Get extends Controller {

    public function authorized(? array & $requester, array & $args ) : bool {
        return $requester && count( $requester[ 'moderator' ] ) > 0; // only if logged in and moderates any districts
    }

    public function process(Request $request, array $args) : mixed
    {
        $moderatorId = $args['moderatorId' ];
        $districts =  queries\moderator\districts\Select::execute( $moderatorId );
        return [ 'districts'=>$districts ];
    }

}