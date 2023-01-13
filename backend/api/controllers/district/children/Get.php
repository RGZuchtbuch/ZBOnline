<?php

namespace App\controllers\district\children;

use App\queries;
use App\controllers\Controller;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class Get extends Controller
{
    public function authorized(?array &$requester, array &$args): bool
    {
        return true;
    }

    public function process(Request $request, array $args) : mixed
    {
        $districtId = $args['districtId'];
        $root = queries\district\children\Select::execute( $districtId );
        return [ 'rootDistrict'=>$root ];
    }
}
