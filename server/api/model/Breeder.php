<?php

namespace App\model;

use App\controller\Controller;
use Slim\Exception\HttpNotImplementedException;

class Breeder extends Query
{


    public static function get( int $id = null ) : ? array {
		if( $id ) { // not null
			$args = get_defined_vars();
			$stmt = Query::prepare('
				SELECT id, member, firstname, infix, lastname, email, districtId, club, start, end, info
				FROM user
				WHERE id=:id
			');
			return Query::select($stmt, $args);
		} else { // list
			$stmt = Query::prepare('
				SELECT id, member, firstname, infix, lastname, email, districtId, club, start, end, info
				FROM user
				ORDER BY lastname, infix, firstname
			');
			return Query::selectArray($stmt );
		}
    }

    public static function new( ? string $member, string $firstname, ? string $infix, string $lastname, ? string $email, int $districtId, ? string $club, ? string $start, ? string $end, ? string $info, int $modifierId ) : ? int {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            INSERT INTO user ( member, firstname, infix, lastname, email, districtId, club, `start`, `end`, info, modifierId )
            VALUES ( :member, :firstname, :infix, :lastname, :email, :districtId, :club, :start, :end, :info, :modifierId )
        ' );
        return Query::insert( $stmt, $args );
    }

    public static function set( int $id, ? string $member, string $firstname, ? string $infix, string $lastname, ? string $email, ? string $club, ? string $start, ? string $end, ? string $info, int $modifierId ) : bool {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            UPDATE user
            SET member=:member, firstname=:firstname, infix=:infix, lastname=:lastname, email=:email, club=:club, `start`=:start, `end`=:end, info=:info, modifierId=:modifierId
            WHERE id=:id  
        ');
        return Query::update($stmt, $args);
    }

    public static function del( int $id ) : bool { // TODO use with care, as it's could be referred to from reports
        $args = get_defined_vars();
        $stmt = Query::prepare('
            DELETE FROM user 
            WHERE id=:id
        ');
        return Query::delete($stmt, $args);
    }


	public static function getAll() : array {
		$args = get_defined_vars();
		$stmt = Query::prepare('
            SELECT id, member, firstname, infix, lastname, email, districtId, club, start, end, info
            FROM user
        ');
		return Query::selectArray($stmt, $args);
	}

    public static function getName( int $id ) : ? array {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            SELECT id, firstname, infix, lastname
            FROM user
            WHERE id=:id
        ');
        return Query::select($stmt, $args);
    }

    public static function getPairs( int $breederId ) : array { // TODO move to pair!
        $args = get_defined_vars();
        $stmt = Query::prepare('
            SELECT pair.id, pair.year, pair.group, pair.districtId,
                user.firstname, user.infix, user.lastname, user.member, 
                pair.sectionId, pair.breedId, pair.colorId, pair.name, breed.name AS breedName, color.name AS colorName,
                result.layEggs, result.layWeight, result.broodEggs, result.broodFertile, result.broodHatched, result.showScore
            FROM pair
            LEFT JOIN breed ON breed.id = pair.breedId
            LEFT JOIN color ON color.id = pair.colorId
            LEFT JOIN result ON result.pairId = pair.id
            LEFT JOIN user ON user.id = pair.breederId
            WHERE pair.breederId=:breederId
            ORDER BY pair.year DESC, pair.name
        ');
        return Query::selectArray($stmt, $args);
    }

	public static function getPairsInYear( int $breederId, int $year ) {
		$args = get_defined_vars();
		$stmt = Query::prepare( " 
            SELECT pair.id, pair.breederId, pair.year, pair.name, 
				layEggs, layWeight, 
				broodEggs, broodFertile, broodHatched,
				showCount, showScore
            FROM pair
            LEFT JOIN result ON result.pairId = pair.id
            WHERE pair.breederId=:breederId AND pair.year=:year
            ORDER BY pair.year, name
        " );
		return Query::selectArray( $stmt, $args );
	}
}
