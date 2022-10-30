<?php

namespace App\Queries;

use App\routes\Controller;

class Report
{



    public static function delete( int $id ) : bool {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( "
            DELETE FROM report WHERE id=:$id
        ");
        return Query::delete( $stmt, $args );
    }

}

/*
    public static function updatea( array $pair ) : bool {
        $success = true;
        Query::begin();
            Report::updatePair( $pair['id'], $pair['breederId'], $pair['year'], $pair['name'], $pair['group'], $pair['sectionId'], $pair['breedId'], $pair['colorId'], $pair['paired'], $pair['notes'] );
            Report::updateParents( $pair['id'], $pair['parent'] );
        if( $success ) {
            Query::commit();
        } else {
            Query::rollback();
        }
        return $success;
    }
 */