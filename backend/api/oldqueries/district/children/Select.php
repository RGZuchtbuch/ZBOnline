<?php

namespace App\queries\district\children;

use App\queries\BaseModel;
use App\routes\Controller;
use Exception;
use http\Exception\BadMessageException;
use http\Exception\BadUrlException;
use PDOException;

class Select extends BaseModel
{
    public static function execute( ...$args ) : ? array {
        $args = static::validate( ...$args );
        try {
            $stmt = static::prepare('
                SELECT id, parentId, name FROM district WHERE id=:districtId
                UNION
                SELECT id, parentId, name FROM district WHERE parentId=:districtId
                ORDER BY name
            ');
            return static::selectRoot($stmt, $args);
        } catch( PDOException $e ) {
            throw new PDOException( $e->getMessage() );
        }
    }

    private static function validate( int $districtId ) : array {
        if( $districtId>0 ) {
            return get_defined_vars();
        };
        throw new BadUrlException( "Error in query args");
    }
}