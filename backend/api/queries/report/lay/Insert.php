<?php

namespace App\queries\report\lay;

use App\queries\Query;
use App\controllers\Controller;
use http\Exception\BadMessageException;

class Insert extends Query
{
    public static function execute (...$args ) : bool {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            INSERT INTO report_lay ( reportId, `start`, `end`, eggs, dames, weight, modifier )
            VALUES ( :reportId, :start, :end, :eggs, :dames, :weight, :modifier )
        ' );
        return static::insert( $stmt, $args );
    }

    private static function validate( int $reportId, ? string $start, ? string $end, ? int $eggs, ? int $dames, ? int $weight ) : array {
        if( $reportId>0 ) {
            $modifier = Controller::$requester['id']; // add to def vars
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}