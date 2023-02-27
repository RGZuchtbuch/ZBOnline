<?php

namespace App\Model;



use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpNotImplementedException;

class District extends Model
{
    public static function authorized( int $id, int $requesterId ) : bool {
        return true;
    }

    public static function new( string $name, int $sectionId, int $broodGroup, int $lay, int $eggWeight, int $sireRing, int $dameRing, int $sireWeight, int $dameWeight, string $info ) : ? int {
        throw new HttpNotImplementedException( null, "oops" );
    }

    public static function get( int $id ) : ? array {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            SELECT district.*, moderator.userId as moderatorId 
            FROM district
            LEFT JOIN moderator ON moderator.districtId = district.id
            WHERE id=:id
        ' );
        return Query::select($stmt, $args);
    }


    public static function set( int $id, string $name, int $sectionId, int $broodGroup, int $lay, int $eggWeight, int $sireRing, int $dameRing, int $sireWeight, int $dameWeight, string $info ) : bool {
        throw new HttpNotImplementedException( null, "oops" );
    }

    public static function del( int $id ) : bool {
        throw new HttpNotImplementedException( null, "oops" );
    }

    public static function children( int $id ) : array {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            SELECT id, parentId, name FROM district WHERE id=:id
            UNION
            SELECT id, parentId, name FROM district WHERE parentId=:id
            ORDER BY name
        ');
        return Query::selectArray( $stmt, $args );
    }

    public static function descendants( int $id ) : array {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            WITH RECURSIVE districts( id, parentId, name, moderatable, level ) AS (
               SELECT id, parentId, name, moderatable, level FROM district WHERE id=:id
               UNION
               SELECT district.id, district.parentId, district.name, district.moderatable, district.level
               FROM districts JOIN district ON district.parentId=districts.id
            )
            SELECT * FROM districts
            ORDER BY districts.name
        ' );
        return Query::selectArray( $stmt, $args );
    }
}
