<?php

namespace App\controllers\map;

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

    public function process(Request $request, array $args, array $query ) : mixed
    {
        $districts = null;
        $query = $request->getQueryParams();
        $year = $query[ 'year' ] ?? null;
        if( $colorId = $query[ 'color' ] ?? null ) {
            $districts = queries\map\color\Select::execute($year, $colorId);
        } else if( $breedId = $query[ 'breed' ] ?? null ) {
            $districts = queries\map\breed\Select::execute($year, $breedId);
        } else if( $sectionId = $query[ 'section' ] ?? null ) {
            $districts = queries\map\section\Select::execute($year, $sectionId);
        }

        return [ 'districts'=>$districts ];
    }
}