<?php

namespace App\Queries\moderator\districts;

use App\queries\BaseModel;
use http\Exception\BadMessageException;

class Select extends BaseModel
{
    public static function execute( ...$args ) : ? array {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
             WITH RECURSIVE districts( parentId, id, name, latitude, longitude ) AS (
                 SELECT parentId, id, name, latitude, longitude
                  FROM district
                  LEFT JOIN moderator ON moderator.districtId = district.id
                  WHERE district.moderatable AND moderator.userId=:userId
                  
                 UNION
                 
                 SELECT district.parentId, district.id, district.name, district.latitude, district.longitude
                 FROM districts JOIN district ON district.parentId=districts.id
                 WHERE district.moderatable
             )
             SELECT * FROM districts
             ORDER BY name
        ' );
        return static::selectTree( $stmt, $args );
    }

    private static function validate( int $userId ) : array {
        if( $userId ) {
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}