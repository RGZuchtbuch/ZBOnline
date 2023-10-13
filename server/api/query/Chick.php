<?php

namespace App\query;

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

    public static function new( int $pairId, ? int $broodId, string $ring, ? string $ringed, int $modifierId ) : ? int {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            INSERT INTO pair_chicks ( pairId, broodId, ring, ringed, modifierId ) 
            VALUES ( :pairId, :broodId, :ring, :ringed, :modifierId )
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