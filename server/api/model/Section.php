<?php

namespace App\model;

use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpNotImplementedException;

class Section
{

    public static function get( int $id = null ) : ? array {
		if( $id ) {
			$args = get_defined_vars();
			$stmt = Query::prepare('
				SELECT id, name, parentId, layers, `order`
				FROM section
				WHERE id=:id
			');
			return Query::select($stmt, $args);
		} else {
			$stmt = Query::prepare('
				SELECT id, name, parentId, layers, `order`
				FROM section
				ORDER BY `order`, name
			');
			return Query::selectArray($stmt);
		}
    }

	public static function new( string $name, int $sectionId, int $broodGroup, int $lay, int $eggWeight, int $sireRing, int $dameRing, int $sireWeight, int $dameWeight, string $info, int $modifierId ) : ? int {
		throw new HttpNotImplementedException( null, "oops" );
	}

	public static function set( int $id, string $name, int $sectionId, int $broodGroup, int $lay, int $eggWeight, int $sireRing, int $dameRing, int $sireWeight, int $dameWeight, string $info, int $modifierId ) : bool {
        throw new HttpNotImplementedException( null, "oops" );
    }

    public static function del( int $id ) : bool {
        throw new HttpNotImplementedException( null, "oops" );
    }

	// other getters

    public static function getBreeds(int $sectionId ) : array {
        $args = get_defined_vars();
        $stmt = Query::prepare( "
            SELECT 
                breed.id, breed.name, section.name AS sectionName
            FROM breed
                LEFT JOIN section ON section.id=breed.sectionId
            WHERE breed.sectionId IN (
                SELECT DISTINCT child.id FROM section AS parent
                    LEFT JOIN section AS child ON child.id = parent.id OR child.parentId = parent.id
                WHERE parent.id=:sectionId OR parent.parentId = :sectionId  
            )
            ORDER BY breed.name
        ");
        return Query::selectArray( $stmt, $args );
    }

    public static function getChildren( int $id ) : array {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            SELECT id, name, parentId, layers, `order`
            FROM section
            WHERE parentId=:id
        ');
        return Query::selectArray($stmt, $args);
    }

    public static function getDescendants( int $sectionId ) : array { // including root
        $args = get_defined_vars();
        $stmt = Query::prepare( "
            SELECT DISTINCT child.id, child.parentId, child.name, child.layers, child.order FROM section AS parent
                LEFT JOIN section AS child ON child.id = parent.id OR child.parentId = parent.id
            WHERE parent.id=:sectionId OR parent.parentId=:sectionId
            ORDER BY child.order
        ");

        return Query::selectArray( $stmt, $args );
    }
}
