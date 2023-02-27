<?php

namespace App\Model;

use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpNotImplementedException;

class Section extends Model
{
    public static function authorized( int $id, int $requesterId ) : bool {
        return true;
    }

    public static function new( string $name, int $sectionId, int $broodGroup, int $lay, int $eggWeight, int $sireRing, int $dameRing, int $sireWeight, int $dameWeight, string $info ) : ? int {
        throw new HttpNotImplementedException( null, "oops" );
    }

    public static function get( int $id ) : ? array {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            SELECT id, name, parentId, layers, `order`
            FROM section
            WHERE id=:id
        ');
        return Query::select($stmt, $args);
    }


    public static function set( int $id, string $name, int $sectionId, int $broodGroup, int $lay, int $eggWeight, int $sireRing, int $dameRing, int $sireWeight, int $dameWeight, string $info ) : bool {
        throw new HttpNotImplementedException( null, "oops" );
    }

    public static function del( int $id ) : bool {
        throw new HttpNotImplementedException( null, "oops" );
    }

    public static function breeds( int $id ) : array {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            WITH RECURSIVE sections( id, childId, NAME ) AS 
            (
               SELECT id, id AS childId, NAME FROM section WHERE id=:id
                UNION
               SELECT sections.id AS id, section.id AS childId, sections.name AS NAME #root name !
               FROM sections JOIN section ON section.parentId=sections.childId
            )
            
            SELECT Breed.id, Breed.name, sections.name AS sectionName
            FROM sections
            LEFT JOIN Breed ON Breed.sectionId=sections.childId
            WHERE Breed.id IS NOT NULL
            ORDER BY Breed.name
        ');
        return Query::selectArray($stmt, $args);
    }

    public static function children( int $id ) : array {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            SELECT id, name, parentId, layers, `order`
            FROM section
            WHERE parentId=:id
        ');
        return Query::selectArray($stmt, $args);
    }

    public static function descendants( int $id ) : array {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            WITH RECURSIVE sections( id, parentId, name, layers, `order` ) AS (
                SELECT id, parentId, name, layers, `order` FROM section WHERE id=:id
                UNION
                SELECT section.id, section.parentId, section.name, section.layers, section.order 
                FROM sections JOIN section ON section.parentId=sections.id
            )
            SELECT * FROM sections
            ORDER BY sections.order
        ' );
        return Query::selectArray( $stmt, $args );
    }
}
