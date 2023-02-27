<?php

namespace App\queries\report\parent;

use App\queries\BaseModel;
use App\controllers\Controller;
use http\Exception\BadMessageException;

class Update extends BaseModel
{
    public static function execute( ...$args ) : bool {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            UPDATE report_parent 
            SET reportId=:reportId, sex=:sex, ring=:ring, score=:score, modifier=:modifier
            WHERE id=:id    
        ' );
        return BaseModel::update( $stmt, $args );
    }

    private static function validate( int $id, int $reportId, string $sex, ? string $ring, ? float $score ) : array {
        if( $id>0 && $reportId>0 && ( $sex === '1.0' || $sex === '0.1' ) ) {
            $modifier = Controller::$requester['id']; // add to def vars
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}