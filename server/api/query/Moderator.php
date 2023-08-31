<?php

namespace App\query;



use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;

class Moderator extends Query
{


    public static function new( int $userId, int $districtId ) : ? int {
        return null;
    }

    public static function get( int $id ) : ? array {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            SELECT id, firstname, infix, lastname, email
            FROM user
            WHERE id=:id
        ');
        return Query::select($stmt, $args);
    }


    public static function set( int $userId, int $districtId ) : ? int {
        return false;
    }

    public static function del( int $id ) : bool {
        return false;
    }


    public static function districts( int $moderatorId ) : array {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            SELECT id
            FROM district
            WHERE moderatorId=:moderatorId
        ');
        return Query::selectArray($stmt, $args);
    }

}