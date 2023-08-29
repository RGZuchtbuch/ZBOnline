<?php

namespace App\Controller\Pair;

use App\Controller\Controller;
use App\Query;
use Exception;
use PDOException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpInternalServerErrorException;

class Post extends Controller
{
    public function authorized(): bool
    {
        if( $this->requester && $this->data ) {
            if( $this->requester['admin'] ) return true; // admin
            if( count( $this->requester[ 'moderator' ] ) > 0 ) return true; // any moderator
        }
        return false;
    }

    public function process() : array
    {
        Query\Query::begin(); // begin transaction
        try {
            Query\Cache::del('pairs'); // clear cache
            Query\Cache::del('results');
            $pair = $this->data;
            $id = $pair['id'] ?? null;
            if ($id) {
                Query\Pair::set($pair['id'], $pair['breederId'], $pair['districtId'], $pair['year'], $pair['group'], $pair['sectionId'], $pair['breedId'], $pair['colorId'], $pair['name'], $pair['paired'], $pair['notes'], $this->requester['id']);
            } else { // id being 0 for new
                $id = Query\Pair::new($pair['breederId'], $pair['districtId'], $pair['year'], $pair['group'], $pair['sectionId'], $pair['breedId'], $pair['colorId'], $pair['name'], $pair['paired'], $pair['notes'], $this->requester['id']);
            }
            // TODO for all parts...
            $this->updateElders($pair);
            $this->updateLay($pair);
            $this->updateBroods($pair);
            $this->updateShow($pair);

            $this->updateResult( $pair );

            Query\Query::commit();
            return ['id' => $id];
        } catch( PDOException $e ) {
            Query\Query::rollback();
            throw $e; //new HttpInternalServerErrorException( $this->request, $e->getTraceAsString().'<br><br><br>'.$e->getMessage() );
        }
        //return ['id' => null];
    }

    private function updateElders( & $pair ) {
        Query\Elder::delForPair( $pair['id'] );
        $elders = $pair['elders'];
        if( $elders ) {
            foreach( $elders as & $elder ) {
                if( $elder['sex'] && $elder['ring'] ) {
                    Query\Elder::new( $pair['id'], $elder['sex'], $elder['ring'], $elder['score'], $elder['pair'], $this->requester['id'] );
                }
            }
        }

    }

    private function updateLay( & $pair ) {
        Query\Lay::delForPair( $pair['id'] );
        $lay = $pair[ 'lay' ];
        if( $pair['sectionId'] !== 5 && $lay && $lay['start'] && $lay['end'] && $lay[ 'eggs' ] ) { // only if valid
            Query\Lay::new($pair['id'], $lay['start'], $lay['end'], $lay['eggs'], $lay['dames'], $lay['weight'], $this->requester['id'] );
        }
    }

    private function updateBroods( & $pair ) {
        Query\Chick::delForPair( $pair['id'] );
        Query\Brood::delForPair( $pair['id'] );

        if( isset( $pair['broods'] ) ) {
            $broods = & $pair['broods'];
            foreach( $broods as & $brood ) {
                if( $pair['sectionId'] === 5 ) {
                    $brood['eggs'] = 2;
                    $brood['fertile'] = null;
                }
                if( $brood['eggs'] && $brood['hatched'] && ( $brood['hatched'] <= ( $brood['fertile'] ? $brood['fertile'] : $brood['eggs'] ) ) ) {
                    $brood['id'] = Query\Brood::new( $pair['id'], $brood['start'], $brood['eggs'], $brood['fertile'], $brood['hatched'], $this->requester['id'] );
                    foreach( $brood['chicks'] as & $chick ) {
                        if( $chick['ring'] ) {
                            Query\Chick::new($pair['id'], $brood['id'], $chick['ring'], $this->requester['id']);
                        }
                    }
                }
            }
        }
    }


    private function updateShow( & $pair ) {
        Query\Show::delForPair( $pair['id'] );
        $show = $pair[ 'show' ] ?? null;
        if( $show ) { // only if valid
            Query\Show::new($pair['id'], $show['89'], $show['90'], $show['91'], $show['92'], $show['93'], $show['94'], $show['95'], $show['96'], $show['97'], $this->requester['id'] );
        }
    }

    private function updateResult( & $pair ) {
        Query\Result::delForPair( $pair['id'] );

        $broods = & $pair['broods'];
        $broodEggs = null;
        $broodFertile = null;
        $broodHatched = null;
        foreach( $broods as & $brood ) {
            if( $brood['eggs'] && $brood['hatched'] ) {
                $broodEggs += $brood['eggs'];
                $broodHatched += $brood['hatched'];
                if( $brood['fertile'] ) {
                    $broodFertile += $brood['fertile'];
                }
            }
        }

        $show = & $pair['show'];
        $showCount = $show['89']+$show['90']+$show['91']+$show['92']+$show['93']+$show['94']+$show['95']+$show['96']+$show['97'];
        $showScore = null;
        if( $showCount ) {
            $showTotalScore = 89 * $show['89'] + 90 * $show['90'] + 91 * $show['91'] + 92 * $show['92'] + 93 * $show['93'] + 94 * $show['94'] + 95 * $show['95'] + 96 * $show['96'] + 97 * $show['97'];
            $showScore = $showTotalScore / $showCount;
        }

        if( $pair['sectionId'] === 5 ) { // pidgeon, no lay
            Query\Result::new(
                $pair['id'], $pair['districtId'], $pair['year'],
                $pair['group'], $pair['breedId'], $pair['colorId'],
                1, 1,
                null, null, null,
                2, null, $broodHatched,
                $showCount, $showScore,
                $this->requester['id']
            );

        } else { // layers
            Query\Result::new(
                $pair['id'], $pair['districtId'], $pair['year'],
                $pair['group'], $pair['breedId'], $pair['colorId'],
                1, 1,
                $pair['lay']['dames'], $pair['lay']['eggs'], $pair['lay']['weight'],
                $broodEggs, $broodFertile, $broodHatched,
                $showCount, $showScore,
                $this->requester['id']
            );

        }
    }
}
