<?php

namespace App\Queries;
use PDO;
use PDOStatement;

class Brood
{
    public static function insertAll( int $reportId, array $broods ): ?int
    {
        Brood::deleteForReport( $reportId ); // remove all old

        $success = true;
        $stmt = Query::prepare('
            INSERT INTO pair_brood ( pairId, `index`, start, eggs, fertile, hatched )
            VALUES ( :reportId, :index, :start, :eggs, :fertile, :hatched )
        ');

        foreach( $broods as $index => $brood) {
            $success &= Brood::insert($stmt, $reportId, $index, $brood['start'], $brood['eggs'], $brood['fertile'], $brood['hatched'] );
        }
    }

    private static function insert( PDOStatement $stmt, int $reportId, int $index, ?string $start, int $eggs, int $fertile, int $hatched ) : bool {
        $args = get_defined_vars(); // all vars in scope
        return Query::insert( $stmt, $args ) != null; // Q::insert returns new id or null on failure
    }

    private static function deleteForReport(int $reportId): bool
    {
        $args = get_defined_vars(); // all vars in scope
        $stmt = Query::prepare('
            DELETE FROM pair_brood WHERE reportId=:reportId 
        ');
        return Query::delete($stmt, $args);
    }

}
