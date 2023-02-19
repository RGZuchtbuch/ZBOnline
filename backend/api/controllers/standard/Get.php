<?php

namespace App\controllers\standard;

use App\queries;
use App\controllers\Controller;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class Get extends Controller {

    public function authorized(? array $requester, array $args, array $query ) : bool {
        return true;
    }

    public function process(Request $request, array $args, array $query) : mixed
    {
        $root =  queries\standard\Select::execute(); // 2 being geflügel
        return [ 'section'=>$root ];
    }

}