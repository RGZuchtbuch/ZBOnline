<?php

namespace App\Query;

class Chick extends Query
{
    public static function get( $id ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            SELECT id, pairId, broodId, ring
            FROM pair_chicks
            WHERE id=:id
        ' );
        return Query::select( $stmt, $args );
    }

    public static function new( int $pairId, ? int $broodId, string $ring, int $modifierId ) : ? int {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            INSERT INTO pair_chicks ( pairId, broodId, ring, modifierId ) 
            VALUES ( :pairId, :broodId, :ring, :modifierId )
        ' );
        return Query::insert( $stmt, $args );
    }

    // no set as al renewed at report save

    public static function getForBrood( int $broodId ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            SELECT id, pairId, broodId, ring
            FROM pair_chicks
            WHERE broodId=:broodId
            ORDER BY ring
        ' );
        return Query::selectArray( $stmt, $args );
    }

    public static function delForPair( int $pairId ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            DELETE 
            FROM pair_chicks
            WHERE pairId=:pairId
        ' );
        return Query::delete( $stmt, $args );
    }



}