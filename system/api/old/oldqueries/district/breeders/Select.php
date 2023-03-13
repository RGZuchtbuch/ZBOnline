<?php

namespace App\queries\district\breeders;

use App\queries\BaseModel;
use App\routes\Controller;
use http\Exception\BadMessageException;

class Select extends BaseModel
{
    private static function validate( int $districtId ) : array {
        if( $districtId>0 ) {
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }

    public static function execute( ...$args ) : ? array { // TODO, give too much detail ( needs change in frontend as well
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            SELECT user.id, user.name, email, districtId, district.name AS districtName, start, end, clubId, club.name AS clubName, info 
            FROM user 
            LEFT JOIN district ON district.id = user.districtId
            LEFT JOIN district AS club ON club.id = user.clubId
            WHERE districtId=:districtId
        ' );
        return static::selectArray( $stmt, $args );
    }


}