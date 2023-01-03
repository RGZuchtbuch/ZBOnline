<?php

namespace App\controllers\district\results;

use App\queries;
use App\controllers\Controller;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class Get extends Controller
{
    public function preAuthorized(?array &$requester, array &$args): bool
    {
        return true;
    }

    public function process(Request $request, array $args) : array
    {
        $districtId = $args['districtId'];
        $year = $args['year'];
        $district = queries\district\Select::execute( $districtId );
        $results = $this->tree( queries\district\results\Select::execute( $districtId, $year ) );
        return [ 'district'=>$district, 'results'=>$results, 'debug'=>'test' ];
    }

    private function tree( $results ) : array
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
            if( $row[ 'breedId' ] !== $breedId ) { // next breed
                $breedId = $row[ 'breedId' ];
                unset( $breed ); // to loose ref
                $breed = [ 'id'=>$breedId, 'name'=>$row[ 'breedName' ], 'colors'=>[] ];
                $section[ 'breeds' ][] = & $breed; // new breed array
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
