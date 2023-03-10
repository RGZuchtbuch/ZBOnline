<?php

namespace App\Controller\District;

use App\Model;
use App\Controller\Controller;
use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

class Results extends Controller
{
    public function authorized(): bool
    {
        return true;
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
            $district = Model\District::get( $districtId );
            if( $sectionId && $breedId && $year && $group) { // 3
                $debug[] = 'breed && sectionId = '.$sectionId;
                if( $sectionId == 5 ) {
                    $debug[] = 'pigeon '.$sectionId;
                    $results = Model\District::breedResult($districtId, $breedId, $year, $group); // per breed (5) or colors
                } else {
                    $debug[] = 'other '.$sectionId;
                    $results = Model\District::colorResults($districtId, $breedId, $year, $group); // per breed (5) or colors
                }
            } else if( $sectionId && $year && $group) { // 2
                $debug[] = 'section '.$sectionId;
                $results = Model\District::sectionResults( $districtId, $sectionId, $year, $group );
            } else if( $year ) { // 1, for results listing
                $debug[] = 1;
                $results = $this->resultsTree( Model\District::results( $districtId, $year ) );
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
        $section = null;
        $breedId = 0;
        $breed = null;

        foreach ($results as $row) {
            if( $row['sectionId'] !== $sectionId ) { // next section
                $sectionId = $row['sectionId'];
                unset( $section ); // to loose ref
                $section = [ 'id'=>$sectionId, 'name'=>$row['sectionName'], 'breeds'=>[] ];
                $tree[ 'sections'][] = & $section; // new section array
            }
            if( $row[ 'breedId' ] !== $breedId ) { // next Breed
                $breedId = $row[ 'breedId' ];
                unset( $breed ); // to loose ref
                $breed = [ 'id'=>$breedId, 'name'=>$row[ 'breedName' ], 'colors'=>[] ];
                $section[ 'breeds' ][] = & $breed; // new Breed array
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
}
