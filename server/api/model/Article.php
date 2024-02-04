<?php

namespace App\model;

use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;

class Article
{
	public static function get( int $id ) : ? array {
		$args = get_defined_vars();
		$stmt = Query::prepare('
            SELECT id, title, html
            FROM article
            WHERE id=:id
        ');
		return Query::select($stmt, $args);
	}
    public static function new( string $title, string $html, $modifierId ) : ? int {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            INSERT INTO article ( title, `html`, modifierId )
            VALUES (:title, :html, :modifierId )
        ' );
        return Query::insert( $stmt, $args ); // returns id
    }
    public static function set( int $id,  string $title, string $html, $modifierId ) : bool {
		$args = get_defined_vars();
        $stmt = Query::prepare( '
            UPDATE article
            SET title=:title, html=:html, modifierId=:modifierId
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



    // for list, without html
    public static function getAll() : array {
        $stmt = Query::prepare('
            SELECT id, title
            FROM article
            ORDER BY level
        ');
        return Query::selectArray($stmt );
    }

}
