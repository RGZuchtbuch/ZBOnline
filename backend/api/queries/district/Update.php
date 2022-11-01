<?php

namespace App\queries\district;

use App\queries\Query;
use App\routes\Controller;
use http\Exception\BadMessageException;

class Update extends Query
{
    public static function execute ( ...$args ) : bool {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            UPDATE district 
            SET parentId=:parentId, name=:name, fullname=:fullname, short=:short, coordinates=:coordinates, modifier=:modifier
            WHERE id=:id   
        ' );
        return static::update( $stmt, $args );
    }

    private static function validate( int $id, ? int $parentId, string $name, string $fullname, string $short, ? string $coordinates ) : array {
        if( $id>0 && $parentId>0 && strlen($name)>2 && strlen($fullname)>2 && strlen($short)>0 ) {
            $modifier = Controller::$requester['id']; // add to def vars
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}