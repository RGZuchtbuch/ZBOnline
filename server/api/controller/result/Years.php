<?php

namespace App\controller\result;

use App\query;
use App\controller\Controller;
use App\query\Cache;
use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;

class Years extends Controller
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
        $colorId = $this->query[ 'color' ] ?? null;
        $breedId = $this->query[ 'breed' ] ?? null;
        $sectionId = $this->query[ 'section' ] ?? null;
        $years = [];
        if( $colorId ) {
            $years = query\Result::yearsForColor( $districtId, $colorId );
        } else if( $breedId ) {
            $years = query\Result::yearsForBreed( $districtId, $breedId );
        } else if( $sectionId ) {
            $years = query\Result::yearsForSection( $districtId, $sectionId );
        }
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
