<?php

namespace App\queries\breeder;

use App\queries\Query;
use App\controllers\Controller;
use http\Exception\BadMessageException;

class Update extends Query
{
    public static function execute ( ...$args ) : bool {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            UPDATE user
            SET name=:name, email=:email, districtId=:districtId, clubId=:clubId, start=:start, end=:end, info=:info, modifier=:modifier
            WHERE id=:id   
        ' );
        return static::update( $stmt, $args );
    }

    private static function validate( int $id, string $name, ? string $email, int $districtId, int $clubId, ? string $start, ? string $end, ? string $info ) : array {
        if( $id>0 && strlen( $name )>1 && $districtId>0 && $clubId>0 ) {
            $modifier = Controller::$requester['id']; // add to def vars
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}
