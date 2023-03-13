<?php

namespace App\controllers\district\moderators;

use App\queries;
use App\controllers\Controller;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class Get extends Controller {

    public function authorized(? array $requester, array $args, array $query ) : bool {
        //$user = oldqueries\Authorized::get( $requester, $args['id'] );
        return true;
    }

    public function process(Request $request, array $args, array $query) : mixed
    {
        $moderatorId = $args['moderatorId' ];
        $districts =  queries\moderator\districts\Select::execute( $moderatorId );
        return [ 'districts'=>$districts ];
    }

}