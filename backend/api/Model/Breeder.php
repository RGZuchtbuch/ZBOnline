<?php

namespace App\Model;

use App\Controller\Controller;
use Slim\Exception\HttpNotImplementedException;

class Breeder extends Model
{


    public static function get( int $id ) : ? array {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            SELECT *
            FROM breed
            WHERE id=:id
        ');
        return Query::select($stmt, $args);
    }

    public static function new( string $firstname, ? string $infix, string $lastname, ? string $email, int $districtId, int $clubId, string $start, ? string $end, ? string $info, int $modifier ) : ? int {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            INSERT INTO user ( firstname, infix, lastname, email, districtId, clubId, `start`, `end`, info, modifier )
            VALUES ( :firstname, :infix, :lastname, :email, :districtId, :clubId, :start, :end, :info, :modifier )
        ' );
        return Query::insert( $stmt, $args );
    }

    public static function set( int $id, string $firstname, ? string $infix, string $lastname, ? string $email, int $districtId, int $clubId, string $start, ? string $end, ? string $info, int $modifier ) : ? int {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            UPDATE user
            SET firstname=:firstname, infix=:infix, lastname=:lastname, email=:email, districtId=:districtId, clubId=:clubId, `start`=:start, `end`=:end, info=:info, modifier=:modifier
            WHERE id=:id  
        ');
        return Query::update($stmt, $args);
    }

    public static function delete( int $id ) : ? int { // TODO use with case, as it's could be referred to from reports
        $args = get_defined_vars();
        $stmt = Query::prepare('
            DELETE FROM user 
            WHERE id=:id
        ');
        return Query::delete($stmt, $args);
    }
}
