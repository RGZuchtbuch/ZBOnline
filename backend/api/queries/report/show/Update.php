<?php

namespace App\queries\report\show;

use App\queries\Query;

class Update
{
    public static function execute (
        int $reportId, ? int $s89, ? int $s90, ? int $s91, ? int $s92s, ? int $s93, ? int $s94, ? int $s95, ? int $s96, ? int $s97
    ) : bool {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            UPDATE report_show
            SET `89`=:s89, `90`=:s90, `91`=:s91, `92`=:s92, `93`=:s93, `94`=:s94, `95`=:s95, `96`=:s96, `97`=:s97
            WHERE reportId=:reportId   
        ' );
        return Query::update( $stmt, $args );
    }
}