<?php

namespace App\queries\report;

use App\queries\BaseModel;
use App\controllers\Controller;
use http\Exception\BadMessageException;

class Update extends BaseModel
{
    public static function execute( ...$args ) : bool {
        $args = static::validate( ...$args );
        $stmt = BaseModel::prepare( '
            UPDATE report 
            SET breederId=:breederId, 
                districtId=:districtId, `year`=:year, `group`=:group, 
                sectionId=:sectionId, breedId=:breedId, colorId=:colorId, 
                `name`=:name, paired=:paired, 
                notes=:notes,
                modifier=:modifier
            WHERE id=:id   
        ' );
        return BaseModel::update( $stmt, $args );
    }

    private static function validate(
        int $id, int $breederId, int $districtId, int $year, string $group, int $sectionId, int $breedId, ? int $colorId, ? string $name, ? string $paired, ? string $notes
    ) : array {
        if( $id>0 && $breederId>0 && $districtId>0 &&
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