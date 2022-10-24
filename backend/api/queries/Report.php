<?php

namespace App\Queries;

use App\routes\Controller;

class Report
{

    public static function get( int $id ) : ? array {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            SELECT * FROM report WHERE id=:id
        ' );
        return Query::select( $stmt, $args );
    }

    public static function insert( int $breederId, int $districtId, int $year, string $group, int $sectionId, int $breedId, ? int $colorId, string $name, ? string $paired, ? string $notes ) : bool {
        $modifierId = Controller::$requester['id']; // add to def vars
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            REPLACE INTO report ( breederId, districtId, `year`, `group`, sectionId, breedId, colorId, name, paired, notes, modifier )
            VALUES ( :breederId, :districtId, :year, :group, :sectionId, :breedId, :colorId, :name, :paired, :notes, :modifierId )
         ');
        return Query::insert( $stmt, $args );
    }



    public static function replace( int $id, int $breederId, int $districtId, int $year, string $group, int $sectionId, int $breedId, ? int $colorId, string $name, ? string $paired, ? string $notes ) : bool {
        $modifierId = Controller::$requester['id']; // add to def vars
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            REPLACE INTO report ( id, breederId, districtId, `year`, `group`, sectionId, breedId, colorId, name, paired, notes, modifier )
            VALUES ( :id, :breederId, :districtId, :year, :group, :sectionId, :breedId, :colorId, :name, :paired, :notes, :modifierId )
        ');
        return Query::insert( $stmt, $args );
    }

    public static function delete( int $id ) : bool {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( "
            DELETE FROM report WHERE id=:$id
        ");
        return Query::delete( $stmt, $args );
    }

}

/*
    public static function updatea( array $pair ) : bool {
        $success = true;
        Query::begin();
            Report::updatePair( $pair['id'], $pair['breederId'], $pair['year'], $pair['name'], $pair['group'], $pair['sectionId'], $pair['breedId'], $pair['colorId'], $pair['paired'], $pair['notes'] );
            Report::updateParents( $pair['id'], $pair['parents'] );
        if( $success ) {
            Query::commit();
        } else {
            Query::rollback();
        }
        return $success;
    }
 */