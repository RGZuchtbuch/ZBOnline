<?php

namespace App\Queries;

class Pair
{

    public static function get(int $id ) : ? array {
        $args = [ 'id'=>$id ];
        $stmt = Query::prepare( '
            SELECT * FROM pair WHERE id=:id
        ' );
        return Query::select( $stmt, $args );
    }

    public static function create( int $breederId, string $name, int $year, int $group, int $sectionId, int $breedId, ? int $colorId, ? string $paired, ? string $notes ) : ? int {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            INSERT INTO pair ( breederId, name, year, `group`, sectionId, breedId, colorId, paired, notes )
            VALUES ( :breederId, :name, :year, :group, :sectionId, :breedId, :colorId, :paired, :notes )
        ');
        return Query::insert( $stmt, $args );
    }

    public static function update( int $id, int $breederId, string $name, int $year, int $group, int $sectionId, int $breedId, ? int $colorId, ? string $paired, ? string $notes ) : bool {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            UPDATE pair 
            SET breederId=:breederId, name=:name, year=:year, `group`=:group, sectionId=:sectionId, breedId=:breedId, colorId=:colorId, paired=:paired, notes=:notes
            WHERE id=:id
        ');
        return Query::update( $stmt, $args );
    }

    public static function delete( int $id ) : bool {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            DELETE FROM pair WHERE id=:id 
        ');
        return Query::delete( $stmt, $args );
    }

}