<?php

namespace App\controllers\moderator\districts;

use App\queries;
use App\controllers\Controller;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class Get extends Controller {

    public function authorized(? array $requester, array $args, array $query ) : bool {
        return $requester && count( $requester[ 'moderator' ] ) > 0; // only if logged in and moderates any districts
    }

    public function process(Request $request, array $args, array $query) : mixed
    {
        $moderatorId = Controller::$requester['id']; // current user's districts to moderate
        $districts =  queries\moderator\districts\Select::execute( $moderatorId );
        return [ 'districts'=>$districts ];
    }

}