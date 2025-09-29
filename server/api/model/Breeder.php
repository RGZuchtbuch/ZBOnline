<?php

namespace App\model;

use App\util\Query;

class Breeder
{


    public static function read( int $id = null ) : ? array {
		if( $id ) { // single
			$args = get_defined_vars();
			$stmt = Query::prepare('
				SELECT id, member, firstname, infix, lastname, CONCAT(SUBSTR(firstname, 1, 1), ".", SUBSTR(lastname, 1, 1) ) AS short, email, districtId, club, start, end, active, info
				FROM user
				WHERE id=:id
			');
			return Query::select($stmt, $args);
		}
		return null;
    }

    public static function create(? string $member, string $firstname, ? string $infix, string $lastname, ? string $email, int $districtId, ? string $club, ? string $start, ? string $end, int $active, ? string $info, int $modifierId ) : ? int {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            INSERT INTO user ( member, firstname, infix, lastname, email, districtId, club, `start`, `end`, active, info, modifierId )
            VALUES ( :member, :firstname, :infix, :lastname, :email, :districtId, :club, :start, :end, :active, :info, :modifierId )
        ' );
        return Query::insert( $stmt, $args );
    }

    public static function update(int $id, ? string $member, string $firstname, ? string $infix, string $lastname, ? string $email, ? string $club, ? string $start, ? string $end, int $active, ? string $info, int $modifierId ) : bool {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            UPDATE user
            SET member=:member, firstname=:firstname, infix=:infix, lastname=:lastname, email=:email, club=:club, `start`=:start, `end`=:end, active=:active, info=:info, modifierId=:modifierId
            WHERE id=:id  
        ');
        return Query::update($stmt, $args);
    }

    public static function delete( int $id ) : bool { // TODO use with care, as it's could be referred to from reports
        $args = get_defined_vars();
        $stmt = Query::prepare('
            DELETE FROM user 
            WHERE id=:id
        ');
        return Query::delete($stmt, $args);
    }

	// new svelte 5

	public static function forDistrict( int $districtId ) : array {
		$args = get_defined_vars();
		$stmt = Query::prepare( " 
			SELECT user.id, member, firstname, infix, lastname, email, districtId, district.name AS districtname, club, start, end, active
			FROM user
			LEFT JOIN district ON district.id = user.districtId
			WHERE districtId IN (
				WITH RECURSIVE districts( id ) AS (
					SELECT id FROM district WHERE district.id = :districtId
				UNION ALL
					SELECT child.id	FROM district AS child INNER JOIN districts AS parent ON child.parentId = parent.id
				)
				SELECT * FROM districts
			)
			ORDER BY lastname, firstname
        " );
		return Query::selectArray( $stmt, $args );
	}

}
