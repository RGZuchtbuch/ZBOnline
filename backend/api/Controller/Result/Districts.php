<?php

namespace App\Controller\Result;

use App\Model;
use App\Controller\Controller;
use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;

class Districts extends Controller
{
    public function authorized(): bool
    {
        return true;
    }

    public function process() : array
    {
        $year = $this->query[ 'year' ];
        $colorId = $this->query[ 'color' ] ?? null;
        $breedId = $this->query[ 'breed' ] ?? null;
        $sectionId = $this->query[ 'section' ] ?? null;
        $districts = [];
        if( $colorId ) {
            $districts = Model\Result::districtsForColor( $year, $colorId );
        } else if( $breedId ) {
            $districts = Model\Result::districtsForBreed( $year, $breedId );
        } else if( $sectionId ) {
            $districts = Model\Result::districtsForSection( $year, $sectionId );
        }
        return ['districts' => $districts ];
    }
}
