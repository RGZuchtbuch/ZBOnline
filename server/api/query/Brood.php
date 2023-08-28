<?php

namespace App\Query;

class Brood extends Query
{
    public static function get( $id ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            SELECT id, pairId, start, eggs, fertile, hatched
            FROM pair_brood
            WHERE id=:id
        ' );
        return Query::select( $stmt, $args );
    }

    public static function new( int $pairId, ? string $start, string $eggs, ? float $fertile, ? int $hatched, int $modifierId ) : ? int {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            INSERT INTO pair_brood ( pairId, start, eggs, fertile, hatched, modifierId ) 
            VALUES ( :pairId, :start, :eggs, :fertile, :hatched, :modifierId )
        ' );
        return Query::insert( $stmt, $args );
    }

    // no set as all renewed at report save

    public static function getForPair( $pairId ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            SELECT id, pairId, start, eggs, fertile, hatched
            FROM pair_brood
            WHERE pairId=:pairId
            ORDER BY start
        ' );
        return Query::selectArray( $stmt, $args );
    }

    public static function delForPair( int $pairId ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            DELETE 
            FROM pair_brood
            WHERE pairId=:pairId
        ' );
        return Query::delete( $stmt, $args );
    }

}