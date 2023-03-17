<?php

namespace App\Controller\Result;

use App\Query;
use App\Controller\Controller;
use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;

class Years extends Controller
{
    public function authorized(): bool
    {
        return true;
    }

    public function process() : array
    {
        $districtId = $this->query[ 'district' ];
        $colorId = $this->query[ 'color' ] ?? null;
        $breedId = $this->query[ 'breed' ] ?? null;
        $sectionId = $this->query[ 'section' ] ?? null;
        $years = [];
        if( $colorId ) {
            $years = Query\Result::yearsForColor( $districtId, $colorId );
        } else if( $breedId ) {
            $years = Query\Result::yearsForBreed( $districtId, $breedId );
        } else if( $sectionId ) {
            $years = Query\Result::yearsForSection( $districtId, $sectionId );
        }
        return ['years' => $years];
    }
}
