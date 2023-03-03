<?php

namespace App\queries\standard;

use App\queries\BaseModel;
use http\Exception\BadMessageException;

class Select extends BaseModel
{

    private static function validate() : array {
        return get_defined_vars();
    }

    public static function execute( ...$args ) : ? array {
        $args = static::validate( ...$args );
        $sections = static::sections();
        $breeds = static::breeds();
        $colors = static::colors();
        return static::tree( $sections, $breeds, $colors );
    }

    private static function sections() {
        $args = [];
        $stmt = static::prepare( '
            SELECT id, name, parentId, `order`, layers FROM section ORDER BY `order`
        ');
        return static::selectArray( $stmt, $args );
    }
    private static function breeds() {
        $args = [];
        $stmt = static::prepare( '
            SELECT id, name, sectionId FROM Breed ORDER BY name
        ');
        return static::selectArray( $stmt, $args );
    }
    private static function colors() {
        $args = [];
        $stmt = static::prepare( '
            SELECT id, name, breedId FROM color ORDER BY name
        ');
        return static::selectArray( $stmt, $args );
    }

    public static function tree(& $sectionsRows, & $breedsRows, & $colorsRows ) : array {
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

