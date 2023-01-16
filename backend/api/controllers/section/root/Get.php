<?php

namespace App\controllers\section\root;

use App\queries;
use App\controllers\Controller;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class Get extends Controller {

    public function authorized(? array & $requester, array & $args ) : bool {
        return true;
    }

    public function process(Request $request, array $args) : mixed
    {
        $rootId = $args['sectionId' ];
        $root =  queries\section\root\Select::execute( $rootId );
        return [ 'section'=>$root ];
    }

}