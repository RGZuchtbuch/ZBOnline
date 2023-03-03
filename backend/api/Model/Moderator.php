<?php

namespace App\Model;



use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;

class Moderator extends Model
{


    public static function new( int $userId, int $districtId ) : ? int {
        return null;
    }

    public static function get( int $id ) : ? array {
        return null;
    }


    public static function set( int $userId, int $districtId ) : ? int {
        return false;
    }

    public static function del( int $id ) : bool {
        return false;
    }


    public static function districts( int $id ) : array {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            SELECT userId, districtId
            FROM moderator
            WHERE userId=:id
        ');
        return Query::selectArray($stmt, $args);
    }

}