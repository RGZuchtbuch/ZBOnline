<?php

namespace App\queries\report;

use App\queries\BaseModel;
use App\controllers\Controller;
use http\Exception\BadMessageException;

class Insert extends BaseModel
{
    public static function execute (...$args ) : bool {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            INSERT INTO report ( breederId, districtId, `year`, `group`, sectionId, breedId, colorId, `name`, paired, notes, modifier )
            VALUES ( :breederId, :districtId, :year, :group, :sectionId, :breedId, :colorId, :name, :paired, :notes, :modifier )
        ' );
        return static::insert( $stmt, $args );
    }

    private static function validate(
        int $breederId, int $districtId, int $year, string $group, int $sectionId, int $breedId, ? int $colorId, ? string $name, ? string $paired, ? string $notes
    ) : array {
        if( $breederId>0 && $districtId>0 &&
            ( $year>1900 && $year<9999 ) &&
            ( $group==="I" || $group==="II" || $group==="III" ) &&
            $sectionId>0 && $breedId>0 && (( $sectionId===5 && $colorId===null ) || $colorId>0 )
        ){
            $modifier = Controller::$requester['id']; // add to def vars
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}