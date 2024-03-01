<?php

namespace App\model;

class Pair extends Query
{
    public static function get( $id ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            SELECT id, breederId, districtId, year, `group`, sectionId, breedId, colorId, name, paired, notes
            FROM pair
            WHERE id=:id
        ' );
        return Query::select( $stmt, $args );
    }

    public static function new( int $breederId, int $districtId, int $year, string $group, int $sectionId, int $breedId, ? int $colorId, string $name, ? string $paired, ? string $notes, int $modifierId ) : ? int {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            INSERT INTO pair ( breederId, districtId, year, `group`, sectionId, breedId, colorId, name, paired, notes, modifierId ) 
            VALUES ( :breederId, :districtId, :year, :group, :sectionId, :breedId, :colorId, :name, :paired, :notes, :modifierId )
        ' );
        return Query::insert( $stmt, $args );
    }


    public static function set( int $id, int $breederId, int $districtId, int $year, string $group, int $sectionId, int $breedId, ? int $colorId, string $name, ? string $paired, ? string $notes, int $modifierId ) : bool {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            UPDATE  pair
            SET breederId=:breederId, districtId=:districtId, year=:year, `group`=:group, sectionId=:sectionId, breedId=:breedId, colorId=:colorId, name=:name, paired=:paired, notes=:notes, modifierId=:modifierId
            WHERE id=:id
        ' );
        return Query::update( $stmt, $args );
    }


    public static function del( int $id ) : bool {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            DELETE 
            FROM pair
            WHERE id=:id
        ' );
        return Query::delete( $stmt, $args );
    }





    // for results page
    public static function allWithBreed( int $id ) : array {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            SELECT id, breedId
            FROM pair
            WHERE breedId=:id
        ' );
        return Query::selectArray( $stmt, $args );
    }

    public static function allWithColor( int $id ) : array {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
                SELECT id, colorId
                FROM pair
                WHERE colorId=:id
            ' );
        return Query::selectArray( $stmt, $args );
    }

	//*** Parents ***//

	public static function newParent( int $pairId, int $parentId, ? int $score, int $modifierId ) : ? int {
		$args = get_defined_vars();
		$stmt = Query::prepare( '
            INSERT INTO pairs_x_parents ( pairId, animalId, score, modifierId ) 
            VALUES ( :pairId, :parentId, :score, :modifierId )
        ' );
		return Query::insert( $stmt, $args );
	}

	// no set as all renewed at report save

	public static function getParents( $pairId ) {
		$args = get_defined_vars();
		$stmt = Query::prepare( '
            SELECT id, year, sex, ring, animal.pairId, animal.broodId
            FROM animal
            LEFT JOIN pairs_x_parents 
            ON pairs_x_parents.animalId = animal.id 
            WHERE pairs_x_parents.pairId = :pairId
            ORDER BY sex, year, ring
        ' );
		return Query::selectArray( $stmt, $args );
	}

	public static function delParents( int $pairId ) {
		$args = get_defined_vars();
		$stmt = Query::prepare( '
            DELETE 
            FROM pairs_x_parents
            WHERE pairId=:pairId
        ' );
		return Query::delete( $stmt, $args );
	}

	//*** Lay ***//

	public static function getLay( $pairId ) : ? array {
		$args = get_defined_vars();
		$stmt = Query::prepare( '
            SELECT id, pairId, start, end, eggs, dames, weight
            FROM pair_lay
            WHERE pairId=:pairId
        ' );
		return Query::select( $stmt, $args );
	}

	//** Lay ***/
	public static function newLay( int $pairId, string $start, string $end, int $eggs, ? int $dames, ? int $weight, int $modifierId ) : ? int {
		$args = get_defined_vars();
		$stmt = Query::prepare( '
            INSERT INTO pair_lay ( pairId, start, end, eggs, dames, weight, modifierId ) 
            VALUES ( :pairId, :start, :end, :eggs, :dames, :weight, :modifierId )
        ' );
		return Query::insert( $stmt, $args );
	}

	// no set

	public static function delLay(int $pairId ) {
		$args = get_defined_vars();
		$stmt = Query::prepare( '
            DELETE 
            FROM pair_lay
            WHERE pairId=:pairId
        ' );
		return Query::delete( $stmt, $args );
	}



	//*** Brood ***//

	// no set as all renewed at report save

	public static function getBroods( $pairId ) {
		$args = get_defined_vars();
		$stmt = Query::prepare( '
            SELECT id, pairId, start, eggs, fertile, hatched
            FROM pair_brood
            WHERE pairId=:pairId
            ORDER BY start
        ' );
		return Query::selectArray( $stmt, $args );
	}

	public static function newBrood( int $pairId, ? string $start, string $eggs, ? float $fertile, ? int $hatched, int $modifierId ) : ? int {
		$args = get_defined_vars();
		$stmt = Query::prepare( '
            INSERT INTO pair_brood ( pairId, start, eggs, fertile, hatched, modifierId ) 
            VALUES ( :pairId, :start, :eggs, :fertile, :hatched, :modifierId )
        ' );
		return Query::insert( $stmt, $args );
	}

	public static function delBroods( int $pairId ) {
		$args = get_defined_vars();
		$stmt = Query::prepare( '
            DELETE 
            FROM pair_brood
            WHERE pairId=:pairId
        ' );
		return Query::delete( $stmt, $args );
	}


	//*** Show ***//
	public static function getShow( $pairId ) : ? array {
		$args = get_defined_vars();
		$stmt = Query::prepare( '
            SELECT id, pairId, `89`, `90`, `91`, `92`, `93`, `94`, `95`, `96`, `97`
            FROM pair_show
            WHERE pairId=:pairId
        ' );
		return Query::select( $stmt, $args ); // null or one per pair
	}

	public static function newShow( int $pairId, ? int $p89, ? int $p90, ? int $p91, ? int $p92, ? int $p93, ? int $p94, ? int $p95, ? int $p96, ? int $p97, int $modifierId ) : ? int {
		$args = get_defined_vars();
		$stmt = Query::prepare( '
            INSERT INTO pair_show ( pairId, `89`, `90`, `91`, `92`, `93`, `94`, `95`, `96`, `97`, modifierId ) 
            VALUES ( :pairId, :p89, :p90, :p91, :p92, :p93, :p94, :p95, :p96, :p97, :modifierId )
        ' );
		return Query::insert( $stmt, $args );
	}

	public static function delShow(int $pairId ) : bool {
		$args = get_defined_vars();
		$stmt = Query::prepare( '
            DELETE 
            FROM pair_show
            WHERE pairId=:pairId
        ' );
		return Query::delete( $stmt, $args );
	}

}