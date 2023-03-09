<?php

namespace App\Model;

use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpNotImplementedException;

class Section extends Model
{

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
/*
        $stmt = Query::prepare('
            WITH RECURSIVE sections( id, childId, NAME ) AS
            (
               SELECT id, id AS childId, NAME FROM section WHERE id=:id
                UNION
               SELECT sections.id AS id, section.id AS childId, sections.name AS NAME #root name !
               FROM sections JOIN section ON section.parentId=sections.childId
            )

            SELECT breed.id, breed.name, sections.name AS sectionName
            FROM sections
            LEFT JOIN breed ON Breed.sectionId=sections.childId
            WHERE breed.id IS NOT NULL
            ORDER BY breed.name
        ');
*/

        $stmt = Query::prepare("
            SELECT breed.id, breed.name, sections.name AS sectionName
            FROM (
                SELECT section_sorted.*
                FROM (
                    select * from section ORDER BY parentId, id
                ) section_sorted, (
                    SELECT @pv:=:id AS root
                ) initialisation
                WHERE ( find_in_set(parentId, @pv) > 0 AND @pv := CONCAT(@pv, ',', id) ) OR id=:id     
            ) AS sections
            LEFT JOIN breed ON breed.sectionId=sections.id
            WHERE breed.id IS NOT NULL
            ORDER BY breed.name
        ");
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
/*
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
*/
        $stmt = Query::prepare("
            SELECT id, parentId, name, layers, `order`
            FROM (
                SELECT sorted.*
                FROM (
                    select * from section ORDER BY parentId, id
                ) sorted, (
                    SELECT @pv:=:id AS root
                ) init
                WHERE ( find_in_set(parentId, @pv) > 0 AND @pv := CONCAT(@pv, ',', id) ) OR id=:id     
            ) AS sections                    
            ORDER BY sections.order
        " );

        return Query::selectArray( $stmt, $args );
    }
}
