<?php

namespace App\Model;

use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;

class Standard extends Model
{
    public static function new( string $title, string $text ) : ? int {
        return null;
    }

    public static function get( int $id ) : ? array {
        $args = get_defined_vars();
        $stmt = Query::prepare('
            SELECT id, title, text
            FROM article
            WHERE id=:id
        ');
        return Query::select($stmt, $args);
    }


    public static function set( int $id,  string $title, string $text ) : bool {
        return false;
    }

    public static function del( int $id ) : bool {
        return false;
    }

}