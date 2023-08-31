<?php

namespace App\controller\result;

use App\query;
use App\controller\Controller;
use App\query\Cache;
use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

class District extends Controller
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
        $districtId = $this->args['id'] ?? null;
        $sectionId = $this->query[ 'section' ] ?? null;
        $breedId = $this->query[ 'breed' ] ?? null;
        $year = $this->query[ 'year' ] ?? null;
        $group = $this->query[ 'group' ] ?? null;
        $district = null;
        $results = null;
        $debug = [];
        if( $districtId ) { // TODO when used, only case 1, last is found for district results.
            $district = query\District::get( $districtId );
            if( $sectionId && $breedId && $year && $group) { // 3
                $debug[] = 'breed && sectionId = '.$sectionId;
                if( $sectionId == 5 ) {
                    $debug[] = 'pigeon '.$sectionId;
                    $results = query\District::breedResult($districtId, $breedId, $year, $group); // per breed (5) or colors
                } else {
                    $debug[] = 'other '.$sectionId;
                    $results = query\District::colorResults($districtId, $breedId, $year, $group); // per breed (5) or colors
                }
            } else if( $sectionId && $year && $group) { // 2
                $debug[] = 'section '.$sectionId;
                $results = query\District::sectionResults( $districtId, $sectionId, $year, $group );
            } else if( $year ) { // 1, for results listing
                $debug[] = 1;
                $results = $this->resultsTree( query\District::results( $districtId, $year ) );
            } else {
                throw new HttpBadRequestException( $this->request, "wrong arguments");
            }

        }
        return [ 'district'=>$district, 'results'=>$results, 'debug'=>$debug ];
    }

    private function resultsTree( $results ) : array
    {
        $tree = [ 'sections'=>[] ];
        $sectionId = 0;
        $subsectionId = 0;
        $section = null;
        $subsection = null;
        $breedId = 0;
        $breed = null;

        foreach ($results as $row) {
            if( $row['sectionId'] !== $sectionId ) { // next section
                $sectionId = $row['sectionId'];
                unset( $section ); // to lose ref
                $section = [ 'id'=>$sectionId, 'name'=>$row['sectionName'], 'subsections'=>[] ];
                $tree[ 'sections'][] = & $section; // new section array
            }
            if( $row['subsectionId'] !== $subsectionId ) { // next section
                $subsectionId = $row['subsectionId'];
                unset( $subsection ); // to lose ref
                $subsection = [ 'id'=>$subsectionId, 'name'=>$row['subsectionName'], 'breeds'=>[] ];
                $section[ 'subsections'][] = & $subsection; // new section array
            }
            if( $row[ 'breedId' ] !== $breedId ) { // next Breed
                $breedId = $row[ 'breedId' ];
                unset( $breed ); // to loose ref
                $breed = [ 'id'=>$breedId, 'name'=>$row[ 'breedName' ], 'colors'=>[] ];
                $subsection[ 'breeds' ][] = & $breed; // new Breed array
            }
            $result = [
                'id'=>$row['id'], 'breeders'=>$row['breeders'], 'pairs'=>$row['pairs'],
                'layDames'=>$row['layDames'], 'layEggs'=>$row['layEggs'], 'layWeight'=>$row['layWeight'],
                'broodEggs'=>$row['broodEggs'], 'broodFertile'=>$row['broodFertile'], 'broodHatched'=>$row['broodHatched'],
                'showCount'=>$row['showCount'], 'showScore'=>$row['showScore']
            ];
            if( $row['colorId'] === null ) {
                $breed[ 'result' ] = $result;
            } else {
                $breed['colors'][] = [
                    'id' => $row['colorId'], 'name' => $row['colorName'], 'result'=> $result
                ];
            }
        }
        return $tree;
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
