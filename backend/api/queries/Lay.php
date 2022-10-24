<?php

namespace App\Queries;

use App\queries\Query;
use App\routes\Controller;

class Lay
{

    public static function get( int $reportId ) : ? array {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            SELECT * FROM report_lay WHERE reportId=:reportId
        ' );
        return Query::select( $stmt, $args );
    }

    public static function insert( int $reportId, string $start, string $end, int $eggs, int $dames, ? int $weight  ) : bool {
        $modifierId = Controller::$requester['id']; // add to def vars
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            REPLACE INTO report_lay ( reportId, start, end, eggs, dames, weight, modifier )
            VALUES ( :reportId, :start, :end, :eggs, :dames, :weight, :modifierId )
        ');
        return Query::insert( $stmt, $args );
    }

    public static function replace( int $reportId, ? string $start, ? string $end, ? int $eggs, ? int $dames, ? int $weight  ) : bool {
        $modifierId = Controller::$requester['id']; // add to def vars
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            REPLACE INTO report_lay ( reportId, start, end, eggs, dames, weight, modifier )
            VALUES ( :reportId, :start, :end, :eggs, :dames, :weight, :modifierId )
        ');
        return Query::replace( $stmt, $args );
    }

    public static function update( int $reportId, string $start, string $end, int $eggs, int $dames, ? int $weight  ) : bool {
        $modifierId = Controller::$requester['id']; // add to def vars
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            REPLACE INTO report_lay ( reportId, start, end, eggs, dames, weight, modifier )
            VALUES ( :reportId, :start, :end, :eggs, :dames, :weight, :modifierId )
        ');
        return Query::insert( $stmt, $args );
    }

    public static function delete( int $reportId ) : bool {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            DELETE FROM report_lay WHERE reportId=:reportId
        ');
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