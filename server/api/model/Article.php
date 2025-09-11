<?php

namespace App\model;

use App\util\Query;

class Article
{
	public static function read(int $id = null ) : ? array {
		if( $id ) {
			$args = get_defined_vars();
			$stmt = Query::prepare('
				SELECT id, level, author, title, html, modified 
				FROM article
				WHERE id=:id
			');
			return Query::select($stmt, $args);
		}
//		else {
//			$stmt = Query::prepare('
//				SELECT id, level, author, title
//				FROM article
//				ORDER BY level
//			');
//			return Query::selectArray($stmt );
//		}
		return null;
	}

    public static function create( ? int $level, string $author, string $title, string $html, $modifierId ) : ? int {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            INSERT INTO article ( level, author, title, `html`, modifierId )
            VALUES ( :level, :author, :title, :html, :modifierId )
        ' );
        return Query::insert( $stmt, $args ); // returns id
    }
    public static function update(int $id, ? int $level, string $author, string $title, string $html, $modifierId ) : bool {
		$args = get_defined_vars();
        $stmt = Query::prepare( '
            UPDATE article
            SET level=:level, author=:author, title=:title, html=:html, modifierId=:modifierId
            WHERE id=:id
        ' );
        return Query::update( $stmt, $args );
    }
    public static function delete(int $id ) : bool {
		$args = get_defined_vars();
		$stmt = Query::prepare( '
            DELETE FROM article WHERE id=:id
        ' );
		return Query::delete( $stmt, $args );
    }

	public static function all() : array {
		$stmt = Query::prepare('
				SELECT id, level, author, title, modified
				FROM article
				ORDER BY level
			');
		return Query::selectArray($stmt );
	}
}
