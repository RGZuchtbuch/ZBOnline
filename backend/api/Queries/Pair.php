<?php

namespace App\Queries;

use PDO;
use PDOStatement;
use App\routes\Controller;

class Pair
{

    public static function get(int $id ) : ? array {
        $args = [ 'id'=>$id ];
        $stmt = Query::prepare( '
            SELECT * FROM pair WHERE id=:id
        ' );
        $pair = Query::select( $stmt, $args );

        return $pair;
    }

    public static function getLay(int $id ) : ? array {
        $args = [ 'id'=>$id ];
        $stmt = Query::prepare( '
            SELECT * FROM pair_lay WHERE pairId=:id
        ' );
        return Query::select( $stmt, $args );
    }


    public static function getBroods(int $id ) : ? array {
        $args = [ 'id'=>$id ];
        $stmt = Query::prepare( '
            SELECT * FROM pair_brood WHERE pairId=:id
        ' );
        $broods = Query::selectArray( $stmt, $args );
        foreach( $broods as & $brood ) {
            $args = [ 'pairId'=>$id, 'index'=>$brood['index'] ];
            $stmt = Query::prepare( '
                SELECT * FROM pair_chick WHERE pairId=:pairId AND `index`=:index
            ' );
            $brood['chicks'] = Query::selectArray( $stmt, $args );
        }
        return $broods;
    }


    public static function getChicks(int $id ) : ? array {
        $args = [ 'id'=>$id ];
        $stmt = Query::prepare( '
            SELECT * FROM pair_chick WHERE pairId=:id
        ' );
        return Query::selectArray( $stmt, $args );
    }

    public static function getParents(int $id ) : ? array {
        $args = [ 'id'=>$id ];
        $stmt = Query::prepare( '
            SELECT * FROM pair_parent WHERE pairId=:id
        ' );
        return Query::selectArray( $stmt, $args );
    }

    public static function getShow(int $id ) : ? array {
        $args = [ 'id'=>$id ];
        $stmt = Query::prepare( '
            SELECT * FROM pair_show WHERE pairId=:id
        ' );
        return Query::select( $stmt, $args );
    }



    public static function insert( int $breederId, int $year, string $name, int $group, int $sectionId, int $breedId, ? int $colorId, ? string $paired, ? string $notes ) : ? int {
        $modifier = Controller::$requester; // add to def vars
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            INSERT INTO pair ( breederId, year, name, `group`, sectionId, breedId, colorId, paired, notes, modifier )
            VALUES ( :breederId, :year, :name, :group, :sectionId, :breedId, :colorId, :paired, :notes, :modifier )
        ');
        return Query::insert( $stmt, $args );
    }

    public static function update( array $pair ) : bool {
        $success = true;
        Query::begin();
            Pair::updatePair( $pair['id'], $pair['breederId'], $pair['year'], $pair['name'], $pair['group'], $pair['sectionId'], $pair['breedId'], $pair['colorId'], $pair['paired'], $pair['notes'] );
            Pair::updateParents( $pair['id'], $pair['parents'] );
        if( $success ) {
            Query::commit();
        } else {
            Query::rollback();
        }
        return $success;
    }


    public static function updatePair( int $id, int $breederId, int $year, ? string $name, string $group, int $sectionId, int $breedId, ? int $colorId, ? string $paired, ? string $notes ) : bool {
        $modifier = Controller::$requester['id'];
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( '
            UPDATE pair 
            SET breederId=:breederId, name=:name, year=:year, `group`=:group, sectionId=:sectionId, breedId=:breedId, colorId=:colorId, paired=:paired, notes=:notes, modifier=:modifier
            WHERE id=:id
        ');
        return Query::update( $stmt, $args );
    }

    public static function delete( int $id ) : bool {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare( "
            DELETE FROM pair WHERE id=:id
        ");
        return Query::delete( $stmt, $args );
    }


    //Spring


    public static function updateParents(int $pairId, array $parents): bool
    {
        $modifier = Controller::$requester['id'];
        $success = true;
        $success &= Pair::deleteParents($pairId); // remove all old

        $stmt = Query::prepare('
            INSERT INTO pair_parent ( pairId, `index`, sex, ring, score, parentsId, modifier )
            VALUES ( :pairId, :index, :sex, :ring, :score, :parentsId, :modifier )
        ');

        foreach ($parents as $index => $parent) {
            if( $parent['ring'] ) {
                $success &= Pair::insertParent($stmt, $pairId, $index, $parent['sex'], $parent['ring'], $parent['score'], $parent['parentsId'], $modifier);
            }
        }
        return $success;
    }

    private static function insertParent(PDOStatement $stmt, int $pairId, int $index, string $sex, string $ring, ? int $score, ?int $parentsId): bool
    {
        $args = [ 'pairId'=>$pairId, 'index'=>$index, 'sex'=>$sex, 'ring'=>$ring, 'score'=>$score, 'parentsId'=>$parentsId, 'modifier'=>Controller::$requester['id'] ];
        return Query::insert($stmt, $args) != null; // Q::insert returns new id or null on failure
    }

    private static function deleteParents(int $pairId): bool
    {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare('
            DELETE FROM pair_parent WHERE pairId=:pairId 
        ');
        return Query::delete($stmt, $args);
    }



}