<?php

namespace App\query;

class Lay extends Query
{
    public static function get( $id ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            SELECT id, pairId, start, end, eggs, dames, weight
            FROM pair_lay
            WHERE id=:id
        ' );
        return Query::select( $stmt, $args );
    }

    public static function new( int $pairId, string $start, string $end, int $eggs, ? int $dames, ? int $weight, int $modifierId ) : ? int {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            INSERT INTO pair_lay ( pairId, start, end, eggs, dames, weight, modifierId ) 
            VALUES ( :pairId, :start, :end, :eggs, :dames, :weight, :modifierId )
        ' );
        return Query::insert( $stmt, $args );
    }

    // no set as al renewed at report save

    public static function getForPair( $pairId ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            SELECT id, pairId, start, end, eggs, dames, weight
            FROM pair_lay
            WHERE pairId=:pairId
        ' );
        return Query::select( $stmt, $args );
    }

    public static function delForPair(int $pairId ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            DELETE 
            FROM pair_lay
            WHERE pairId=:pairId
        ' );
        return Query::delete( $stmt, $args );
    }





}