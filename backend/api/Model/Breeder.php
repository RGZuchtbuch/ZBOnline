<?php

namespace App\Model;

use Slim\Exception\HttpNotImplementedException;

class Breeder extends Model
{
    public static function authorized( int $id, int $requesterId ) : bool {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            SELECT user.id, user.districtId 
            FROM user LEFT JOIN moderator ON moderator.districtId = user.districtId
            WHERE user.id=:id AND moderator.userId=:requesterId
        ' );
        return Query::select( $stmt, $args ) != null; // false if no valid combi found
    }

    public static function new( string $firstname, ? string $infix, string $lastname, ? string $email, int $districtId, int $clubId, string $start, ? string $end, ? string $info ) : ? int {
       return null;
    }

    public static function get( int $id ) : ? array {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            SELECT *
            FROM breed
            WHERE id=:id
        ');
        return Query::select($stmt, $args);
    }


    public static function set( int $id, string $firstname, ? string $infix, string $lastname, ? string $email, int $districtId, int $clubId, string $start, ? string $end, ? string $info ) : ? int {
        return false;
    }

    public static function del( int $id ) : ? int {
        return false;
    }
}