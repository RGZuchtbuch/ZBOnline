<?php

namespace App\Query;

class Elder extends Query
{
    public static function get( $id ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            SELECT id, pairId, sex, ring, score, pair
            FROM pair_elder
            WHERE id=:id
        ' );
        return Query::select( $stmt, $args );
    }

    public static function new( int $pairId, string $sex, string $ring, ? float $score, ? int $pair, int $modifierId ) : ? int {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            INSERT INTO pair_elder ( pairId, sex, ring, score, pair, modifierId ) 
            VALUES ( :pairId, :sex, :ring, :score, :pair, :modifierId )
        ' );
        return Query::insert( $stmt, $args );
    }

    // no set as all renewed at report save

    public static function getForPair( $pairId ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            SELECT id, pairId, sex, ring, score, pair
            FROM pair_elder
            WHERE pairId=:pairId
            ORDER BY sex, ring
        ' );
        return Query::selectArray( $stmt, $args );
    }

    public static function delForPair( int $pairId ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            DELETE 
            FROM pair_elder
            WHERE pairId=:pairId
        ' );
        return Query::delete( $stmt, $args );
    }





}