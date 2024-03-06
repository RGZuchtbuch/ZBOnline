<?php

namespace App\controller\result;

use App\model;
use App\controller\Controller;
use App\model\Cache;
use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;

class DistrictYears extends Controller
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
        $districtId = $this->query[ 'district' ];
        $sectionId  = $this->query[ 'section' ] ?? null;
        $breedId    = $this->query[ 'breed' ] ?? null;
        $colorId    = $this->query[ 'color' ] ?? null;
        $group      = $this->query[ 'group' ] ?? null;
        $years      = [];

        $years = model\Result::getResultsDistrictYears( $districtId, $sectionId, $breedId, $colorId, $group );
/*
        if( $colorId ) {
//            $years = query\Result::yearsForColor( $districtId, $colorId );
            $years = query\Result::resultsDistrictYears( $districtId,null, null, $colorId, null );
        } else if( $breedId ) {
//            $years = query\Result::yearsForBreed( $districtId, $breedId );
            $years = query\Result::resultsDistrictYears( $districtId,null, $breedId, null, null );
        } else if( $sectionId ) {
//            $years = query\Result::yearsForSection( $districtId, $sectionId );
            $years = query\Result::resultsDistrictYears( $districtId, $sectionId, null, null, null );
        }
*/
        return ['years' => $years];
    }

    private function getCacheParams() : string {
        $districtId = $this->query[ 'district' ];
        $sectionId = $this->query[ 'section' ] ?? null;
        $breedId = $this->query[ 'breed' ] ?? null;
        $colorId = $this->query[ 'color' ] ?? null;
        return 'district:'.$districtId.', section:'.$sectionId.', breed:'.$breedId.', color:'.$colorId;
    }
}
