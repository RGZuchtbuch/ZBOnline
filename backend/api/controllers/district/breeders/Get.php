<?php

namespace App\controllers\district\breeders;

use App\queries;
use App\controllers\Controller;
use Exception;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;

class Get extends Controller {

    public function authorized(?array $requester, array $args, array $query): bool {
        return queries\district\breeders\Authorized::execute( $requester[ 'id' ], $args[ 'districtId' ] );
        //return $requester && in_array($args['districtId'], $requester['moderator']); // only if logged in and moderates any districts
    }

    public function process(Request $request, array $args, array $query) : mixed
    {
        $districtId = $args['districtId'];
        $breeders = queries\district\breeders\Select::execute( $districtId );
        return [ 'breeders'=>$breeders ];
    }
}