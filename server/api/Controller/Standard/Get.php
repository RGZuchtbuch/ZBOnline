<?php

namespace App\Controller\Standard;

use App\Model;
use App\Controller\Controller;
use App\Model\Query;
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

    public function process() : array
    {
        $sections = $this->sections();
        $breeds = $this->breeds();
        $colors = $this->colors();
        $standard = $this->toStandardTree( $sections, $breeds, $colors );
        return ['standard' => $standard];
    }

    /** Helpers **/

    private function sections() {
        $args = [];
        $stmt = Query::prepare( '
            SELECT id, name, parentId, `order`, layers FROM section ORDER BY `order`
        ');
        return Query::selectArray( $stmt, $args );
    }
    private function breeds() {
        $args = [];
        $stmt = Query::prepare( '
            SELECT id, name, sectionId FROM Breed ORDER BY name
        ');
        return Query::selectArray( $stmt, $args );
    }
    private function colors() {
        $args = [];
        $stmt = Query::prepare( '
            SELECT id, name, breedId FROM color ORDER BY name
        ');
        return Query::selectArray( $stmt, $args );
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
