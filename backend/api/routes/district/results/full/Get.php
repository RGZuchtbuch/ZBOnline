<?php

namespace App\routes\district\results\full;

use App\Queries;
use App\routes\Controller;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class Get extends Controller
{
    public function preAuthorized(?array &$requester, array &$args): bool
    {
        return true;
    }



    public function process(Request $request, array $args) : mixed
    {
        $districtId = $args['districtId'];
        $sectionId = $args['sectionId'];
        $year = $args['year'];
        $group = $args['group'];
        $results = queries\District::getSectionFullResults( $districtId, $sectionId, $year, $group );
        //$colors;
        return [ 'district'=>$this->convert($results) ];
    }

    private function convert( & $results ) {

        $district = null;
        $section = null;
        $breed = null;

        foreach ( $results as & $result ) {
            if( $district === null || $result['districtId'] !== $district['id'] ) { // new district
                $district = $this->makeDistrict( $result );
            }
            if( $section === null || $result['sectionId'] !== $section['id'] ) { // new section
                unset( $section ); // decouple reference
                $section = $this->makeSection( $result );
                $district[ 'section' ] = & $section;
            }
            if( $breed === null || $result['breedId'] !== $breed['id'] ) { // new breed
                unset( $breed ); // decouple reference
                $breed = $this->makeBreed( $result );
                $section['breeds'][] = & $breed;
            }
            if( $result['colorId'] === null ) { // breed template (pigeon )
                $breed['template'] = $this->makeResult( $result );
            } else { // color and template for layers
                $color = $this->makeColor( $result );
                $color['template'] = $this->makeResult( $result );
                $breed['colors'][] = $color;
            }
        }
        return $district;
    }

    private function makeDistrict( & $result ): array {
        return ['id'=>$result['districtId'], 'name'=>$result['districtName'] ];
    }
    private function makeSection( & $result ): array {
        return ['id'=>$result['sectionId'], 'name'=>$result['sectionName'], 'breeds'=>[] ];
    }

    private function makeBreed( array & $result ): array {
        return [ 'id'=>$result['breedId'], 'name'=>$result['breedName'], 'colors'=>[] ];
    }
    private function makeColor( array & $result ): array {
        return [ 'id'=>$result['colorId'], 'name'=>$result['colorName'] ];
    }
    private function makeResult( array & $result ): array {
 //       if( $template['sectionId'] === 5 ) {
 //           return [
 //               'id' => $template['id'],
 //               'breeders' => $template['breeders'], // zuchten
 //               'brood' => $this->makeBroodPigeons( $template ),
 //               'show' => $this->makeShow( $template )
 //           ];
 //       } else {
            return [
                'id' => $result['resultId'],
                'breeders' => $result['breeders'],
                'group' => $result['group'],
                'lay' => $this->makeLay( $result ),
                'brood' => $this->makeBroodLayers( $result ),
                'show' => $this->makeShow( $result )
            ];
 //       }
    }

    private function makeLay( & $result ) {
        return [
            'dames' => $result['layDames'],
            'eggs' => $result['layEggs'],
            'weight' => $result['layWeight']
        ];
    }

    private function makeBroodLayers(& $result ) {
        return [
            'eggs' => $result['broodEggs'],
            'fertile' => $result['broodFertile'],
            'hatched' => $result['broodHatched']
        ];
    }

    private function makeBroodPigeons(& $result ) {
        return [
            'pairs' => $result['broodPairs'], // brutpaare
            'eggs' => $result['broodEggs'], // nests * 2
            'hatched' => $result['broodHatched'] // jungtiere
        ];
    }

    private function makeShow( & $result ) {
        return [
            'count' => $result['showCount'],
            'score' => $result['showScore']
        ];
    }
}
