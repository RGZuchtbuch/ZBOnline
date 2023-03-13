<?php

namespace App\queries\report\lay;

use App\queries\BaseModel;
use App\controllers\Controller;
use http\Exception\BadMessageException;

class Update extends BaseModel
{
    public static function execute( ...$args ) : bool {
        $args = static::validate( ...$args );
        $stmt = BaseModel::prepare( '
            UPDATE report_lay 
            SET `start`=:start, `end`=:end, eggs=:eggs, dames=:dames, weight=:weight, modifier=:modifier
            WHERE reportId=:reportId   
        ' );
        return BaseModel::update( $stmt, $args );
    }

    private static function validate( int $reportId, ? string $start, ? string $end, ? int $eggs, ? int $dames, ? int $weight ) : array {
        if( $reportId>0 ) {
            $modifier = Controller::$requester['id']; // add to def vars
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}