<?php

namespace App\queries\report\brood;

use App\queries\Query;
use App\controllers\Controller;
use http\Exception\BadMessageException;

class Update extends Query
{
    public static function execute ( ...$args ) : bool {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            UPDATE report_brood 
            SET reportId=:reportId, start=:start, eggs=:eggs, fertile=:fertile, hatched=:hatched
            WHERE id=:id   
        ' );
        return static::update( $stmt, $args );
    }

    private static function validate( int $id, int $reportId, ? string $start, ? int $eggs, ? int $fertile, ? int $hatched ) : array {
        if( $id>0 ) {
            $modifier = Controller::$requester['id']; // add to def vars
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}