<?php

namespace App\queries\district;

use App\queries\BaseModel;
use App\controllers\Controller;
use http\Exception\BadMessageException;

class Update extends BaseModel
{
    public static function execute ( ...$args ) : bool {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            UPDATE district 
            SET parentId=:parentId, name=:name, fullname=:fullname, short=:short, latitude=:latitude, longitude=:longitude, modifier=:modifier
            WHERE id=:id   
        ' );
        return static::update( $stmt, $args );
    }

    private static function validate( int $id, ? int $parentId, string $name, string $fullname, string $short, float $latitude, float $longitude ) : array {
        if( $id>0 && $parentId>0 && strlen($name)>2 && strlen($fullname)>2 && strlen($short)>0 && $latitude>45 && $latitude<55 && $longitude>5 && $longitude<15 ) {
            $modifier = Controller::$requester['id']; // add to def vars
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}