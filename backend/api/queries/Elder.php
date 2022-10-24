<?php

namespace App\Queries;

use App\routes\Controller;
use App\Queries\Query;

class Elder
{

    public static function get( int $parentId ) : ? array {
        $args = [ 'id'=>$parentId ];
        $stmt = Query::prepare( '
            SELECT * FROM report_parent WHERE id=:id
        ' );
        return Query::select( $stmt, $args );
    }

    public static function getArray( int $reportId ) : array {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            SELECT * FROM report_parent WHERE reportId=:reportId
        ' );
        return Query::selectArray( $stmt, $args );
    }

    public static function insert( int $reportId, string $sex, ? string $ring, ? float $score ) : bool {
        $modifierId = Controller::$requester['id']; // add to def vars
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            INSERT INTO report_parent ( reportId, sex, ring, score, modifier )
            VALUES ( :reportId, :sex, :ring, :score, :modifierId )
        ');
        return Query::insert( $stmt, $args );
    }

    // no update of elder needed as all elders are replaced by first delete all for reportId

    public static function delete( int $reportId ) : bool {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            DELETE FROM report_parent WHERE reportId=:reportId
        ');
        return Query::delete( $stmt, $args );
    }

}
