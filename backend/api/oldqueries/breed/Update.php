<?php

namespace App\queries\breed;

use App\queries\BaseModel;
use App\routes\Controller;
use http\Exception\BadMessageException;

class Update extends BaseModel
{
    public static function execute ( ...$args ) : bool {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            UPDATE Breed 
            SET name=:name, 
                sectionId=:sectionId, subsectionId=:subsectionId, broodgroup=:broodGroup, image=:image, 
                eggs=:eggs, eggweight=:eggWeight, 
                sirering=:sireRing, damering=:dameRing, 
                sireweight=:sireWeight, dameweight=:dameWeight, 
                info=:info, modifier=:modifier
            WHERE id=:id   
        ' );
        return static::update( $stmt, $args );
    }

    private static function validate(
        int $id, int $name,
        int $sectionId, ? int $subSectionId, int $broodGroup,
        ? string $image,
        ? int $eggs, ? int $eggWeight, ? int $sireRing, ? int $dameRing, ? int $sireWeight, ? int $dameWeight,
        ? string $info
    ) : array {
        if(
            $id > 0 &&
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