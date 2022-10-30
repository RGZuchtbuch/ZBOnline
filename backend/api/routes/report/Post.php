<?php

namespace App\routes\report;

use App\Queries;
use App\routes\Controller;
use DateTime;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

class Post extends Controller
{
    public function preAuthorized( ? array &$requester, array &$args): bool
    {
        return true; //isset( $requester['isAdmin'] ) && $requester['isAdmin'];
    }

    public function process(Request $request, array $args) : mixed
    {
        // first store colors then report with all subs with template id
        $report = $this->getData( $request );

        queries\Query::begin(); // start transaction
        if(
            $this->replaceReport( $report ) && // first as it evt gets the new id
            $this->replaceParents( $report ) &&
            $this->replaceLay( $report ) &&
            $this->replaceBroods( $report ) &&
            $this->replaceShow( $report ) &&

            $this->replaceResult( $report ) // sums it up
        ) {
            queries\Query::commit();
            return [ 'id'=>$report['id'] ];
        } else {
            queries\Query::rollback();
            if( ! $report) throw new HttpBadRequestException( $request, "Could not complete request" );
        }
    }

    private function replaceReport( & $report ) : bool {
        //  int $id, int $breederId, int $districtId, int $year, string $group, int $sectionId, int $breedId, ? int $colorId, string $name, ? string $paired, ? string $notes
        $success = queries\report\Select::execute( $report['id'], $report['breederId'], $report['districtId'], $report['year'], $report['group'], $report['sectionId'], $report['breedId'], $report['colorId'], $report['name'], $report['paired'], $report['notes'] );
        if( $success ) {
            $newId =  queries\Query::lastInsertId( 'id' );
            $report['id'] = $report['id'] == 0 ? $newId : $report['id']; // new or existing id
        }
        return $success;
    }

    private function replaceParents( & $report ) : bool {
        $success = queries\Elder::delete( $report['id'] );
        foreach ( $report[ 'parent'] as $parent) {
            $success &= queries\Elder::insert( $report['id'], $parent['sex'], $parent['ring'], $parent['score'] );
        }
        return $success; // last inserted
    }

    private function replaceLay( & $report ) :bool {
        if( $report['sectionId'] !== 5 ) {
            $lay = $report['lay'];
            return queries\Lay::replace( $report['id'], $lay['start'], $lay['end'], $lay['eggs'], $lay['dames'], $lay['weight'] );
        }
        return true; // case of non layers
    }

    private function replaceBroods( & $report ) :bool {
        $success = queries\Brood::delete( $report['id'] ); // remove all old
        foreach ( $report[ 'broods'] as $brood) {
            $success &= queries\Brood::insert( $report['id'], $brood['start'], $brood['eggs'], $brood['fertile'], $brood['hatched'] );
        }
        return $success; // last inserted
    }

    private function replaceShow( & $report ) : bool {
        $show = $report['show'];
        return queries\Show::replace( $report['id'], $show['89'], $show['90'], $show['91'], $show['92'], $show['93'], $show['94'], $show['95'], $show['96'], $show['97'] );
    }

    protected function replaceResult( & $report ) : bool {
//        $result = $report['result'];
        queries\Result::deleteForReport( $report['id'] );

        $lay = $this->getResultLay( $report );
        $brood = $this->getResultBrood( $report );
        $show = $this->getResultShow( $report );
        return queries\Result::insert(
            $report['id'], $report['districtId'], $report['year'], $report['group'],
            1, 1,
            $report['sectionId'], $report['breedId'], $report['colorId'],
            $lay['dames'], $lay['eggs'], $lay['weight'],
            $brood['eggs'], $brood['fertile'], $brood['hatched'],
            $show['count'], $show['score']
        );
    }




    protected function getResultLay( & $report ) : array {
        $lay = $report['lay'];
        if( $lay['start'] && $lay['end'] && $lay['eggs'] ) {
            $start = new DateTime($lay['start']);
            $end = new DateTime($lay['end']);
            $days = $end->diff($start)->format("%a"); // start and last day included
            $dames = $lay['dames'];
            $eggs = 274 * $lay['eggs'] / $days / $dames; // 274 assumes 3/4 of year productive TODO should be formula
            $weight = $lay['weight'];
            return ['dames' => $dames, 'eggs' => $eggs, 'weight' => $weight];
        } else {
            return ['dames' => null, 'eggs' => null, 'weight' => null];
        }
    }
    protected function getResultBrood( & $report ) : array {
        $total = [ 'eggs'=>0, 'fertile'=>0, 'hatched'=>0 ];
        foreach( $report['broods'] as $brood ) {
            $total[ 'eggs' ] += $brood['eggs'];
            $total['fertile'] += $brood['fertile'];
            $total['hatched'] += $brood['hatched'];
        }
        return $total;
    }
    protected function getResultShow( & $report ) : array{
        $show = $report['show'];
        $count = $show['89']+$show['90']+$show['91']+$show['92']+$show['93']+$show['94']+$show['95']+$show['96']+$show['97'];
        $points = 89*$show['89']+90*$show['90']+91*$show['91']+92*$show['92']+93*$show['93']+94*$show['94']+95*$show['95']+96*$show['96']+97*$show['97'];
        return [ 'count' => $count, 'score' => $count > 0 ? $points / $count : null];
    }

}