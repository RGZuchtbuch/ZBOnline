<?php

namespace App\queries\report\lay;

use App\queries\Query;
use App\routes\Controller;
use http\Exception\BadMessageException;

class Update extends Query
{
    public static function execute( ...$args ) : bool {
        $args = static::validate( ...$args );
        $stmt = Query::prepare( '
            UPDATE report_lay 
            SET reportId=:reportId, `start`=:start, `end`=:end, eggs=:eggs, dames=:dames, weight=:weight, modifier=:modifier
            WHERE id=:id   
        ' );
        return Query::update( $stmt, $args );
    }

    private static function validate( int $id, int $reportId, ? string $start, ? string $end, ? int $eggs, ? int $dames, ? int $weight ) : array {
        if( $id>0 && $reportId>0 ) {
            $modifier = Controller::$requester['id']; // add to def vars
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}