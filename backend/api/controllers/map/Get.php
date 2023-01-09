<?php

namespace App\controllers\map;

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
        $query = $request->getQueryParams();
        $year = $query[ 'year' ] ?? null;
        $sectionId = $query[ 'sectionId' ] ?? null;
        $breedId = $query[ 'breedId' ] ?? null;
        $colorId = $query[ 'colorId' ] ?? null;

        $districts = queries\map\Select::execute( $year, $sectionId );

        return [ 'districts'=>$districts ];
    }
}