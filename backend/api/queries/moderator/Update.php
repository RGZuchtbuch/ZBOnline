<?php

namespace App\queries\moderator;

use App\queries\Query;
use App\routes\Controller;
use http\Exception\BadMessageException;

class Update extends Query
{
    public static function execute ( ...$args ) : bool {
        $args = static::validate( ...$args );
        $stmt = Query::prepare( '
            UPDATE color
            SET breedId=:breedId, name=:name, info=:info, modifier=:modifier
            WHERE id=:id
        ' );
        return Query::update( $stmt, $args );
    }

    private static function validate( int $id, int $breedId, string $name, string $info ) : array  {
        if( $id>0 && $breedId>0 && strlen($name)>2 && strlen($info) < 100000 ) {
            $modifier = Controller::$requester['id']; // add to def vars
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}