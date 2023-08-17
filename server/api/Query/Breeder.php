<?php

namespace App\Query;

use App\Controller\Controller;
use Slim\Exception\HttpNotImplementedException;

class Breeder extends Query
{


    public static function get( int $id ) : ? array {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            SELECT user.id, firstname, infix, lastname, email, districtId, district.name AS districtName, clubId, club.name AS clubName, start, end, info
            FROM user
            LEFT JOIN district ON district.id=user.districtId
            LEFT JOIN district AS club ON club.id=user.clubId
            WHERE user.id=:id
        ');
        return Query::select($stmt, $args);
    }

    public static function new( string $firstname, ? string $infix, string $lastname, ? string $email, int $districtId, ? int $clubId, string $start, ? string $end, ? string $info, int $modifierId ) : ? int {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            INSERT INTO user ( firstname, infix, lastname, email, districtId, clubId, `start`, `end`, info, modifierId )
            VALUES ( :firstname, :infix, :lastname, :email, :districtId, :clubId, :start, :end, :info, :modifierId )
        ' );
        return Query::insert( $stmt, $args );
    }

    public static function set( int $id, string $firstname, ? string $infix, string $lastname, ? string $email, ? int $clubId, string $start, ? string $end, ? string $info, int $modifierId ) : bool {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            UPDATE user
            SET firstname=:firstname, infix=:infix, lastname=:lastname, email=:email, clubId=:clubId, `start`=:start, `end`=:end, info=:info, modifierId=:modifierId
            WHERE id=:id  
        ');
        return Query::update($stmt, $args);
    }

    public static function del( int $id ) : bool { // TODO use with case, as it's could be referred to from reports
        $args = get_defined_vars();
        $stmt = Query::prepare('
            DELETE FROM user 
            WHERE id=:id
        ');
        return Query::del($stmt, $args);
    }

//    public static function results( int $breederId ) : array {
//        return [];
//    }
    public static function pairs( int $breederId ) : array {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            SELECT id, `year`, `group`, sectionId, breedId, colorId, name   
            FROM pair
            WHERE breederId=:breederId
            ORDER BY year, name
        ');
        return Query::selectArray($stmt, $args);
    }
}
