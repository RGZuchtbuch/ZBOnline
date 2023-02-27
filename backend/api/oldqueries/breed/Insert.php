<?php

namespace App\queries\breed;

use App\queries\BaseModel;
use App\routes\Controller;
use http\Exception\BadMessageException;

class Insert extends BaseModel
{
    public static function execute ( ...$args ) : bool {
        $args = static::validate( ...$args );

        $stmt = static::prepare( '
            INSERT INTO Breed ( name, sectionId, subsectionId, broodGroup, image, eggs, eggWeight, sireRing, dameRing, sireWeight, dameWeight, info, modifier )
            VALUES ( :name, :sectionId, :subSectionId, :broodGroup, :image, :eggs, :eggWeight, :sireRing, :dameRing, :sireWeight, :dameWeight, :info, :modifier )
        ' );
        return static::insert( $stmt, $args );
    }

    private static function validate(
        int $name,
        int $sectionId, ? int $subSectionId, int $broodGroup,
        ? string $image,
        ? int $eggs, ? int $eggWeight, ? int $sireRing, ? int $dameRing, ? int $sireWeight, ? int $dameWeight,
        ? string $info
    ) : array {
        if(
            strlen( $name ) > 2 &&
            $sectionId > 0 && ( $subSectionId === null || $subSectionId > 0 ) &&
            $broodGroup>=1 && $broodGroup<=4 &&
            ( $eggWeight===null || ( $eggWeight>0 && $eggWeight < 400 ) ) &&
            ( $sireRing===null || ( $sireRing>0 && $sireRing < 100 ) ) &&
            ( $dameRing===null || ( $dameRing>0 && $dameRing < 100 ) ) &&
            ( $sireWeight===null || ( $sireWeight>0 && $sireWeight < 99999 ) ) &&
            ( $dameWeight===null || ( $dameWeight>0 && $dameWeight < 99999 ) ) &&
            strlen($info) < 100000
         ) {
            $modifier = Controller::$requester['id']; // add to def vars
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}