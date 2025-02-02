<?php

namespace App\model;

use http\Exception\BadMethodCallException;
use Slim\Exception\HttpNotImplementedException;

class Breed
{
	public static function get( int $id = null ) : ? array
	{
		if( $id ) {
			$args = get_defined_vars();
			$stmt = Query::prepare('
				SELECT breed.id, breed.name, sectionId, broodGroup, layEggs, layWeight, sireRing, dameRing, sireWeight, dameWeight, info, section.layers AS layer
				FROM breed
				LEFT JOIN section ON section.id = breed.sectionId
				WHERE breed.id=:id
			');
			return Query::select($stmt, $args);
		} else { // list
			$stmt = Query::prepare('
				SELECT id, name, sectionId, broodGroup, layEggs, layWeight, sireRing, dameRing, sireWeight, dameWeight
				FROM breed
				ORDER BY name
			');
			return Query::selectArray($stmt );
		}
	}

	public static function new(string $name, int $sectionId, ? int $broodGroup, ? int $layEggs, ? int $layWeight, ? int $sireRing, ? int $dameRing, ? int $sireWeight, ? int $dameWeight, ? string $info, int $modifierId ) : ? int
    {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
        INSERT INTO breed ( name, sectionId, broodGroup, layEggs, layWeight, sireRing, dameRing, sireWeight, dameWeight, info, modifierId ) 
            VALUES ( :name, :sectionId, :broodGroup, :layEggs, :layWeight, :sireRing, :dameRing, :sireWeight, :dameWeight, :info, :modifierId )
        ' );
        return Query::insert( $stmt, $args ); // returns id
    }



    public static function set(int $id, string $name, int $sectionId, ? int $broodGroup, ? int $layEggs, ? int $layWeight, ? int $sireRing, ? int $dameRing, ? int $sireWeight, ? int $dameWeight, ? string $info, int $modifierId ) : bool
    {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            UPDATE breed
            SET name=:name, sectionId=:sectionId, broodGroup=:broodGroup, layEggs=:layEggs, layWeight=:layWeight, sireRing=:sireRing, dameRing=:dameRing, sireWeight=:sireWeight, dameWeight=:dameWeight, info=:info, modifierId=:modifierId
            WHERE id=:id
        ' );
        return Query::update( $stmt, $args );
    }

    public static function delete( int $id ) : bool
	{
		$args = get_defined_vars();
		$stmt = Query::prepare('
			DELETE 
			FROM breed
			WHERE id=:id
        ');
		return Query::delete($stmt, $args);
    }





    public static function getColors(int $id ) : array {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            SELECT id, name, breedId
            FROM color
            WHERE breedId=:id
            ORDER BY name
        ');
        return Query::selectArray($stmt, $args);
    }

	/** api version 2 */

	public static function forSection( $sectionId ) {
		$args = get_defined_vars();
		$stmt = Query::prepare('
			WITH RECURSIVE sections AS (
				SELECT * FROM section WHERE section.id=:sectionId	
				UNION 	
				SELECT child.* FROM section AS child, sections AS parent WHERE child.parentId = parent.id
			)
			SELECT :sectionId AS sectionId, sections.id AS subSectionId, breed.id, breed.name  
			FROM sections JOIN breed ON breed.sectionId = sections.id
			ORDER BY breed.name        
		');
		return Query::selectArray($stmt, $args);
	}

}
