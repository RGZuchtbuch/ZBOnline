<?php

namespace App\controller\standard;

use App\query;
use App\controller\Controller;
use App\query\Cache;
use Exception;
use http\Exception\InvalidArgumentException;
use PDOException;
use Slim\Exception\HttpNotFoundException;

class Get extends Controller
{
    public function authorized(): bool
    {
        return true;
    }

    public function getCache() : ? string {
        return Cache::getJson( 'standard', '' );
    }

    public function setCache( ? string $json ) : bool {
        return Cache::replace( 'standard', '', $json );
    }

    public function process() : array
    {
        $sections = query\Section::descendants(2);
        $breeds = query\Breed::all();
        $colors = query\Color::all();
        $standard = $this->toStandardTree($sections, $breeds, $colors);
        return ['standard' => $standard ];
    }

    private function toStandardTree(& $sectionsRows, & $breedsRows, & $colorsRows ) : array {
        $sections = [];
        $breeds = [];

        foreach($sectionsRows as & $section ) {
            $section[ 'children' ] = [];
            $section[ 'breeds' ] = [];
            $sections[ $section['id'] ] = & $section;
        }

        foreach($sectionsRows as & $section ) {
            $parentId = $section[ 'parentId' ];
            if( $parentId ) {
                $sections[ $parentId ]['children'][] = & $section;
            }
        }

        foreach($breedsRows as & $breed ) {
            $breed[ 'colors' ] = [];
            $breeds[ $breed[ 'id' ] ] = & $breed;
            $sections[ $breed[ 'sectionId' ] ][ 'breeds' ][] = & $breed;
        }

        foreach($colorsRows as & $color ) {
            $breeds[ $color[ 'breedId' ] ][ 'colors' ][] = & $color;
        }

        return $sections[2]; // geflügel
    }

}
