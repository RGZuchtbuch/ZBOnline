<?php

namespace App\queries\report\parent;

use App\queries\BaseModel;
use App\controllers\Controller;
use http\Exception\BadMessageException;

class Insert extends BaseModel
{
    public static function execute (...$args ) : bool {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            INSERT INTO report_parent ( reportId, sex, ring, score, modifier )
            VALUES ( :reportId, :sex, :ring, :score, :modifier )
        ' );
        return static::insert( $stmt, $args );
    }

    private static function validate( int $reportId, string $sex, ? string $ring, ? float $score ) : array {
        if( $reportId>0 && ( $sex === '1.0' || $sex === '0.1' ) ) {
            $modifier = Controller::$requester['id']; // add to def vars
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}