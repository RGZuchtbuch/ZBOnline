<?php

namespace App\controller\result;

use App\query;
use App\controller\Controller;
use App\query\Cache;
use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

class Result extends Controller
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

    /**
     * Handles
     *  - results for district with year for view mode 1
     *  - results per section with year and group for edit 2
     *  - results per breed with year and group for edit 3
     */
    public function process() : array // parent with direct children
    {
        $districtId = $this->args['districtId'] ?? null;
        $year = $this->args['year'] ?? null;

        $sectionId = $this->query[ 'section' ] ?? null;
        $breedId = $this->query[ 'breed' ] ?? null;
        $colorId = $this->query[ 'color' ] ?? null;
        $group = $this->query[ 'group' ] ?? null;

        $debug = [];
        if( $districtId && $year ) { // TODO when used, only case 1, last is found for district results.
            return query\Result::result( $districtId, $year, $sectionId, $breedId, $colorId, $group );
        }
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
