<?php

namespace App\queries\report\brood;

use App\queries\Query;
use App\controllers\Controller;
use http\Exception\BadMessageException;

class Insert extends Query
{
    public static function execute ( ...$args ) : bool {
        $args = static::validate( ...$args );
        $stmt = Query::prepare( '
            INSERT INTO report_brood ( reportId, start, eggs, fertile, hatched, modifier )
            VALUES ( :reportId, :start, :eggs, :fertile, :hatched, :modifier )
        ' );
        return Query::insert( $stmt, $args );
    }

    private static function validate( int $reportId, ? string $start, ? int $eggs, ? int $fertile, ? int $hatched ) : array {
        if( $reportId>0 ) {
            $modifier = Controller::$requester['id']; // add to def vars
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }

}