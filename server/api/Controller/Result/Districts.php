<?php

namespace App\Controller\Result;

use App\Query;
use App\Controller\Controller;
use App\Query\Cache;
use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;

class Districts extends Controller
{
    public function authorized(): bool
    {
        return true;
    }

    public function getCache() : ? string {
        return Cache::getJson( 'results', $this->getCacheParams() );
    }

    public function setCache( ? string $json ) : bool {
        return Cache::replace( 'results', $this->getCacheParams(), $json );
    }


    public function process() : array
    {
        $year = $this->query[ 'year' ];
        $sectionId = $this->query[ 'section' ] ?? null;
        $breedId = $this->query[ 'breed' ] ?? null;
        $colorId = $this->query[ 'color' ] ?? null;
        $districts = [];
        if( $colorId ) {
            $districts = Query\Result::districtsForColor( $year, $colorId );
        } else if( $breedId ) {
            $districts = Query\Result::districtsForBreed( $year, $breedId );
        } else if( $sectionId ) {
            $districts = Query\Result::districtsForSection( $year, $sectionId );
        }
        return ['districts' => $districts ];
    }

    private function getCacheParams() : string {
        $year = $this->query[ 'year' ];
        $sectionId = $this->query[ 'section' ] ?? null;
        $breedId = $this->query[ 'breed' ] ?? null;
        $colorId = $this->query[ 'color' ] ?? null;
        return 'year:'.$year.', section:'.$sectionId.', breed:'.$breedId.', color:'.$colorId;
    }
}
