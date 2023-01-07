<?php

namespace App\controllers\report;

use App\queries;
use App\controllers\Controller;
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

        $success = true; // until failure
        queries\Query::begin(); // start transaction
        if( $report[ 'id' ] ) { // exists so update
            $success = queries\report\Update::execute( $report['id'], $report['breederId'], $report['districtId'], $report['year'], $report['group'], $report['sectionId'], $report['breedId'], $report['colorId'], $report['name'], $report['paired'], $report['notes'] );

            $success &= queries\report\parents\Delete::execute( $report['id'] ); // delete parents in case now less
            foreach ( $report[ 'parents'] as $parent ) {
                $success &= queries\report\parent\Insert::execute( $report['id'], $parent['sex'], $parent['ring'], $parent['score']  );
            }

            $lay = & $report[ 'lay' ];
            $success &= queries\report\lay\Update::execute( $report['id'], $lay['start'], $lay['end'], $lay['eggs'], $lay['dames'], $lay['weight'] );

            $success &= queries\report\broods\Delete::execute( $report['id'] ); // delete old, in case there is less broods
            foreach ( $report[ 'broods'] as $brood ) {
                $success &= queries\report\brood\Insert::execute( $report['id'], $brood['start'], $brood['eggs'], $brood['fertile'], $brood['hatched'] );
            }

            $show = $report[ 'show' ];
            $success &= queries\report\show\Update::execute( $report['id'], $show['89'], $show['90'], $show['91'], $show['92'], $show['93'], $show['94'], $show['95'], $show['96'], $show['97'] );

            $success &= $this->updateResult( $report );

        } else { // so new thus insert

            $success = queries\report\Insert::execute( $report['breederId'], $report['districtId'], $report['year'], $report['group'], $report['sectionId'], $report['breedId'], $report['colorId'], $report['name'], $report['paired'], $report['notes'] );
            $report['id'] =  queries\Query::lastInsertId( 'id' );

            foreach ( $report[ 'parents'] as $parent ) {
                $success &= queries\report\parent\Insert::execute(  $report['id'], $parent['sex'], $parent['ring'], $parent['score']  );
            }

            $lay = & $report[ 'lay' ];
            $success &= queries\report\lay\Insert::execute( $report['id'], $lay['start'], $lay['end'], $lay['eggs'], $lay['dames'], $lay['weight'] );

            foreach ( $report[ 'broods'] as $brood ) {
                $success &= queries\report\brood\Insert::execute( $report['id'], $brood['start'], $brood['eggs'], $brood['fertile'], $brood['hatched'] );
            }

            $show = $report[ 'show' ];
            $success &= queries\report\show\Insert::execute( $report['id'], $show['89'], $show['90'], $show['91'], $show['92'], $show['93'], $show['94'], $show['95'], $show['96'], $show['97'] );

            $success &= $this->insertResult( $report );
        }
        if( $success ) {
            queries\Query::commit();
            return [ 'id'=>$report['id'] ];
        } else {
            queries\Query::rollback();
            throw new HttpBadRequestException( $request, "Could not complete request" );
        }
    }

    protected function insertResult( & $report ) : bool {
        $lay = $this->getResultLay( $report );
        $brood = $this->getResultBrood( $report );
        $show = $this->getResultShow( $report );
        return queries\result\Insert::execute(
            $report['id'], $report['districtId'], $report['year'], $report['group'],
            $report['breedId'], $report['colorId'],
            1, 1, // breeders, pairs
            $lay['dames'], $lay['eggs'], $lay['weight'],
            $brood['eggs'], $brood['fertile'], $brood['hatched'],
            $show['count'], $show['score']
        );
    }


    protected function updateResult( & $report ) : bool {
        $lay = $this->getResultLay( $report );
        $brood = $this->getResultBrood( $report );
        $show = $this->getResultShow( $report );
        return queries\result\report\Update::execute(
            $report['id'], $report['districtId'], $report['year'], $report['group'],
            $report['sectionId'], $report['breedId'], $report['colorId'],
            1, 1, // breeders, pairs
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