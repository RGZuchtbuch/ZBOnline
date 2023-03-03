<?php

namespace App\queries\color;

use App\queries\BaseModel;
use App\routes\Controller;
use http\Exception\BadMessageException;

class Update extends BaseModel
{
    public static function execute ( ...$args ) : bool {
        $args = static::validate( ...$args );
        $stmt = BaseModel::prepare( '
            UPDATE color
            SET breedId=:breedId, name=:name, info=:info, modifier=:modifier
            WHERE id=:id
        ' );
        return BaseModel::update( $stmt, $args );
    }

    private static function validate( int $id, int $breedId, string $name, string $info ) : array  {
        if( $id>0 && $breedId>0 && strlen($name)>2 && strlen($info) < 100000 ) {
            $modifier = Controller::$requester['id']; // add to def vars
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}