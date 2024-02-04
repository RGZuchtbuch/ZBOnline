<?php

namespace App\controller\result;

use App\model;
use App\controller\Controller;
use App\model\Cache;
use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;

class YearDistricts extends Controller
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
        $year      = $this->query[ 'year' ];
        $sectionId = $this->query[ 'section' ] ?? null; // provided or null
        $breedId   = $this->query[ 'breed' ] ?? null;
        $colorId   = $this->query[ 'color' ] ?? null;
        $group     = $this->query[ 'group' ] ?? null;
        $districts = model\Result::resultsYearDistricts( $year, $sectionId, $breedId, $colorId, $group );
/*
        if( $colorId ) {
            $districts = query\Result::districtsForColor( $year, $colorId );
        } else if( $breedId ) {
            $districts = query\Result::districtsForBreed( $year, $breedId );
        } else if( $sectionId ) {
            $districts = query\Result::districtsForSection( $year, $sectionId );
        }
*/
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
