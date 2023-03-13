<?php

namespace App\Controller\Result;

use App\Model;
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
            $years = Model\Result::yearsForColor( $districtId, $colorId );
        } else if( $breedId ) {
            $years = Model\Result::yearsForBreed( $districtId, $breedId );
        } else if( $sectionId ) {
            $years = Model\Result::yearsForSection( $districtId, $sectionId );
        }
        return ['years' => $years];
    }
}
