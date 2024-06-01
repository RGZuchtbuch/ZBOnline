<?php

namespace App\model;

class Log extends Query
{
    public static function new( string $method, string $uri, ? string $query, ? string $body, ? int $requesterId ) : ? int {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            INSERT INTO _log ( method, uri, query, body, modifierId )
            VALUES ( :method, :uri, :query, :body, :requesterId )
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
