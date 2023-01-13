<?php

namespace App\controllers\trend;

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
        $districts = null;
        $query = $request->getQueryParams();
        $districtId = $query[ 'district' ] ?? null;

        if( $colorId = $query[ 'color' ] ?? null ) {
            $years = queries\trend\color\Select::execute($districtId, $colorId);
        } else if( $breedId = $query[ 'breed' ] ?? null ) {
            $years = queries\trend\breed\Select::execute($districtId, $breedId);
        } else if( $sectionId = $query[ 'section' ] ?? null ) {
            $years = queries\trend\section\Select::execute($districtId, $sectionId);
        }

        return [ 'years'=>$years ];

    }
}