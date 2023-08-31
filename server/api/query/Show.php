<?php

namespace App\query;

class Show extends Query
{
    public static function get( $id ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            SELECT id, pairId, `89`, `90`, `91`, `92`, `93`, `94`, `95`, `96`, `97`
            FROM pair_show
            WHERE id=:id
        ' );
        return Query::select( $stmt, $args );
    }

    public static function new( int $pairId, ? int $p89, ? int $p90, ? int $p91, ? int $p92, ? int $p93, ? int $p94, ? int $p95, ? int $p96, ? int $p97, int $modifierId ) : ? int {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            INSERT INTO pair_show ( pairId, `89`, `90`, `91`, `92`, `93`, `94`, `95`, `96`, `97`, modifierId ) 
            VALUES ( :pairId, :p89, :p90, :p91, :p92, :p93, :p94, :p95, :p96, :p97, :modifierId )
        ' );
        return Query::insert( $stmt, $args );
    }

    // no set as al renewed at report save

    public static function getForPair( $pairId ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            SELECT id, pairId, `89`, `90`, `91`, `92`, `93`, `94`, `95`, `96`, `97`
            FROM pair_show
            WHERE pairId=:pairId
        ' );
        return Query::select( $stmt, $args );
    }

    public static function delForPair(int $pairId ) {
        $args = get_defined_vars();
        $stmt = Query::prepare( '
            DELETE 
            FROM pair_show
            WHERE pairId=:pairId
        ' );
        return Query::delete( $stmt, $args );
    }





}