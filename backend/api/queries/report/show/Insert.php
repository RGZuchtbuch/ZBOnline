<?php

namespace App\queries\report\show;

use App\queries\Query;

class Insert
{
    public static function execute (
        int $reportId, ? int $s89, ? int $s90, ? int $s91, ? int $s92s, ? int $s93, ? int $s94, ? int $s95, ? int $s96, ? int $s97
    ) : bool {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            INSERT INTO report_show ( reportId, `89`, `90`, `91`, `92`, `93`, `94`, `95`, `96`, `97` )
            VALUES ( :reportId, :s89, :s90, :s91, :s92, :s93, :s94, :s95, :s96, :s97 )
        ' );
        return Query::insert( $stmt, $args );
    }
}