<?php

namespace App\Queries;

use App\queries\Query;
use App\routes\Controller;

class Show
{

    public static function get( int $reportId ) : ? array {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            SELECT * FROM report_show WHERE reportId=:reportId
        ' );
        return Query::select( $stmt, $args );
    }

    public static function insert( int $reportId, ? int $p89, ? int $p90, ? int $p91, ? int $p92, ? int $p93, ? int $p94, ? int $p95, ? int $p96, ? int $p97  ) : bool {
        $modifierId = Controller::$requester['id']; // add to def vars
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            INSERT INTO report_show ( reportId, `89`, `90`, `91`, `92`, `93`, `94`, `95`, `96`, `97`, modifier )
            VALUES ( :reportId, :p89, :p90, :p91, :p92, :p93, :p94, :p95, :p96, :p97, :modifierId )
        ');
        return Query::insert( $stmt, $args );
    }

    public static function replace( int $reportId, ? int $p89, ? int $p90, ? int $p91, ? int $p92, ? int $p93, ? int $p94, ? int $p95, ? int $p96, ? int $p97  ) : bool {
        $modifierId = Controller::$requester['id']; // add to def vars
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            REPLACE INTO report_show ( reportId, `89`, `90`, `91`, `92`, `93`, `94`, `95`, `96`, `97`, modifier )
            VALUES ( :reportId, :p89, :p90, :p91, :p92, :p93, :p94, :p95, :p96, :p97, :modifierId )
        ');
        return Query::replace( $stmt, $args );
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
            Report::updateParents( $pair['id'], $pair['parent'] );
        if( $success ) {
            Query::commit();
        } else {
            Query::rollback();
        }
        return $success;
    }
 */