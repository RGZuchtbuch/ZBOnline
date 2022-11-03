<?php

namespace App\queries\report\show;

use App\queries\Query;
use App\controllers\Controller;
use http\Exception\BadMessageException;

class Insert extends Query
{
    public static function execute (...$args ) : bool {
        $args = static::validate( ...$args );
        $stmt = static::prepare( '
            INSERT INTO report_show ( reportId, `89`, `90`, `91`, `92`, `93`, `94`, `95`, `96`, `97`, modifier )
            VALUES ( :reportId, :s89, :s90, :s91, :s92, :s93, :s94, :s95, :s96, :s97, :modifier )
        ' );
        return static::insert( $stmt, $args );
    }

    private static function validate( int $reportId, ? int $s89, ? int $s90, ? int $s91, ? int $s92, ? int $s93, ? int $s94, ? int $s95, ? int $s96, ? int $s97 ) : array {
        if( $reportId>0 &&
            ($s89===null || ($s89>0 && $s89<1000000)) &&
            ($s90===null || ($s90>0 && $s90<1000000)) &&
            ($s91===null || ($s91>0 && $s91<1000000)) &&
            ($s92===null || ($s92>0 && $s92<1000000)) &&
            ($s93===null || ($s93>0 && $s93<1000000)) &&
            ($s94===null || ($s94>0 && $s94<1000000)) &&
            ($s95===null || ($s95>0 && $s95<1000000)) &&
            ($s96===null || ($s96>0 && $s96<1000000)) &&
            ($s97===null || ($s97>0 && $s97<1000000))
        ) {
            $modifier = Controller::$requester['id']; // add to def vars
            return get_defined_vars();
        };
        throw new BadMessageException( "Error in query args");
    }
}