<?php

namespace App\controller;

use App\query;
use App\controller\Controller;
use App\query\Cache;
use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

class Test extends Controller
{
    public function authorized(): bool
    {
        return true;
    }

    public function getCache() : ? string {
        return Cache::getJson( 'resultsx', $this->getCacheParams() );
    }

    public function setCache( ? string $json ) : bool {
        return Cache::replace( 'resultsx', $this->getCacheParams(), $json );
    }

    /**
     * Handles
     *  - results for district with year for view mode 1
     *  - results per section with year and group for edit 2
     *  - results per breed with year and group for edit 3
     */
    public function process() : array // parent with direct children
    {
        $districtId = $this->query['district'] ?? null;
        $year       = $this->query['year'] ?? null;

//        $sectionId  = $this->query[ 'section' ] ?? null;
//        $breedId    = $this->query[ 'breed' ] ?? null;
//        $colorId    = $this->query[ 'color' ] ?? null;
//        $group      = $this->query[ 'group' ] ?? null;

        $results = query\Result::resultsDistrictYear( $districtId, $year );
        return [ 'results'=>$results ];
    }


    private function getCacheParams() : string {
        $districtId = $this->args['id'] ?? null;
        $sectionId = $this->query[ 'section' ] ?? null;
        $breedId = $this->query[ 'breed' ] ?? null;
        $year = $this->query[ 'year' ] ?? null;
        $group = $this->query[ 'group' ] ?? null;
        return 'district:'.$districtId.', year:'.$year.', group:'.$group.', section:'.$sectionId.', breed:'.$breedId;
    }
}
