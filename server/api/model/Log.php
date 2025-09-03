<?php

namespace App\model;

use App\util\Query;

class Log extends Query
{
    public static function log( ? string $method, ? string $uri, ? string $query, ? string $body, ? int $requesterId, ? string $message ) : ? int {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            INSERT INTO _log ( method, uri, query, body, modifierId, message )
            VALUES ( :method, :uri, :query, :body, :requesterId, :message )
        ' );
        return Query::insert( $stmt, $args ); // returns id
    }

    public static function next( int $from, int $count ) : array {
        // need to concatenate args as it requires constant ints
        $stmt = Query::prepare('
            SELECT *
            FROM _log
            ORDER BY modified DESC
            LIMIT '.$from.','.$count.'
        ');
        return Query::selectArray($stmt );
    }

	public static function clear( $untilDate ) : bool {
		$args = get_defined_vars();
		$stmt = Query::prepare('
            DELETE FROM _log WHERE modified < :untilDate
        ');
		return Query::delete($stmt, $args );
	}
}
