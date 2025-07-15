<?php

namespace App\model;

use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;

class Article
{
	public static function get( int $id = null ) : ? array {
		if( $id ) {
			$args = get_defined_vars();
			$stmt = Query::prepare('
				SELECT id, level, author, title, html, modified 
				FROM article
				WHERE id=:id
			');
			return Query::select($stmt, $args);
		} else {
			$stmt = Query::prepare('
				SELECT id, level, author, title
				FROM article
				ORDER BY level
			');
			return Query::selectArray($stmt );
		}
	}

    public static function new( string $author, string $title, string $html, $modifierId ) : ? int {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            INSERT INTO article ( author, level, title, `html`, modifierId )
            VALUES (:author, :level, :title, :html, :modifierId )
        ' );
        return Query::insert( $stmt, $args ); // returns id
    }
    public static function set( int $id, string $author, string $title, string $html, $modifierId ) : bool {
		$args = get_defined_vars();
        $stmt = Query::prepare( '
            UPDATE article
            SET author=:author, title=:title, html=:html, modifierId=:modifierId
            WHERE id=:id
        ' );
        return Query::update( $stmt, $args );
    }
    public static function del( int $id ) : bool {
		$args = get_defined_vars();
		$stmt = Query::prepare( '
            DELETE FROM article WHERE id=:id
        ' );
		return Query::delete( $stmt, $args );
    }
}
