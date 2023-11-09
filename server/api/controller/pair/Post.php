<?php

namespace App\controller\pair;

use App\controller\Controller;
use App\query;
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
        query\Cache::del( 'results' );

        query\Query::begin(); // begin transaction
        try {
            query\Cache::del('pairs'); // clear cache
            query\Cache::del('results');
            $pair = $this->data;
            $id = $pair['id'] ?? null;
            if ($id) {
                query\Pair::set($pair['id'], $pair['breederId'], $pair['districtId'], $pair['year'], $pair['group'], $pair['sectionId'], $pair['breedId'], $pair['colorId'], $pair['name'], $pair['paired'], $pair['notes'], $this->requester['id']);
            } else { // id being 0 for new
                $id = query\Pair::new($pair['breederId'], $pair['districtId'], $pair['year'], $pair['group'], $pair['sectionId'], $pair['breedId'], $pair['colorId'], $pair['name'], $pair['paired'], $pair['notes'], $this->requester['id']);
                $pair['id'] = $id; // continue with new id
            }
            // TODO for all parts...
            $this->updateElders($pair);
            $this->updateLay($pair);
            $this->updateBroods($pair);
            $this->updateShow($pair);

            $this->updateResult( $pair );

            query\Query::commit();
            return ['id' => $id];
        } catch( PDOException $e ) {
            query\Query::rollback();
            throw $e; //new HttpInternalServerErrorException( $this->request, $e->getTraceAsString().'<br><br><br>'.$e->getMessage() );
        }
        //return ['id' => null];
    }

    private function updateElders( & $pair ) {
        query\Elder::delForPair( $pair['id'] );
        $elders = $pair['elders'];
        if( $elders ) {
            foreach( $elders as & $elder ) {
                if( $elder['sex'] && $elder['ring'] ) {
                    query\Elder::new( $pair['id'], $elder['sex'], $elder['ring'], $elder['score'], $elder['pair'], $this->requester['id'] );
                }
            }
        }

    }

    private function updateLay( & $pair ) {
        query\Lay::delForPair( $pair['id'] );
        $lay = $pair[ 'lay' ];
        if( $pair['sectionId'] !== 5 && $lay && $lay['start'] && $lay['end'] && $lay[ 'eggs' ] ) { // only if valid
            query\Lay::new($pair['id'], $lay['start'], $lay['end'], $lay['eggs'], $lay['dames'], $lay['weight'], $this->requester['id'] );
        }
    }

    private function updateBroods( & $pair ) {
        query\Chick::delForPair( $pair['id'] ); // remove all for pair to reinsert
        query\Brood::delForPair( $pair['id'] ); // idem

        if( isset( $pair['broods'] ) ) {
            $broods = & $pair['broods'];

            foreach( $broods as & $brood ) {
                if( $pair['sectionId'] === 5 ) { // pigeons
                    $brood['eggs'] = 2;
                    $brood['fertile'] = null;
                    if( $brood['hatched'] !== null && $brood['hatched'] >= 0 && $brood['hatched'] <= $brood['eggs'] ) {
                        $brood['id'] = query\Brood::new( $pair['id'], $brood['start'], $brood['eggs'], $brood['fertile'], $brood['hatched'], $this->requester['id'] );
                    }
                } else { // layers
                    if( $brood['eggs'] !== null && $brood['eggs'] > 0 && $brood['hatched'] !== null && $brood['hatched'] >=0 &&
                        ( $brood['hatched'] <= ( $brood['fertile'] !== null && $brood['fertile'] >= 0 ?  $brood['fertile'] :  $brood['eggs'] ) ) ) {
//                  if( $brood['eggs'] != null && $brood['hatched'] != null && ( $brood['hatched'] <= ( $brood['fertile'] != null ? $brood['fertile'] : $brood['eggs'] ) ) ) {

                        $brood['id'] = query\Brood::new( $pair['id'], $brood['start'], $brood['eggs'], $brood['fertile'], $brood['hatched'], $this->requester['id'] );
                    }
                }

                foreach( $brood['chicks'] as & $chick ) { // save all chicks ( 2 x pigeon )
                    if( $chick['ring'] ) {
                        query\Chick::new($pair['id'], $brood['id'], $chick['ring'], $brood['ringed'], $this->requester['id']);
                    }
                }
            }
        }
    }


    private function updateShow( & $pair ) {
        query\Show::delForPair( $pair['id'] );
        $show = $pair[ 'show' ] ?? null;
        if( $show ) { // only if valid
            query\Show::new($pair['id'], $show['89'], $show['90'], $show['91'], $show['92'], $show['93'], $show['94'], $show['95'], $show['96'], $show['97'], $this->requester['id'] );
        }
    }

    private function updateResult( & $pair ) {
        query\Result::delForPair( $pair['id'] );

        $broods = & $pair['broods'];
        $broodEggs = null;
        $broodFertile = null;
        $broodHatched = null;
        foreach( $broods as & $brood ) {
            if( $brood['eggs'] !== null && $brood['eggs'] > 0 && $brood['hatched'] !== null && $brood['hatched'] >= 0 ) {
                $broodEggs += $brood['eggs'];
                $broodHatched += $brood['hatched'];
                if( $brood['fertile'] !== null && $brood['fertile'] >=0 ) {
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

        if( $pair['sectionId'] === 5 ) { // pidgeon, no lay, no color
            query\Result::new(
                $pair['id'], $pair['breederId'], $pair['districtId'], $pair['year'],
                $pair['group'], $pair['breedId'], null,
                1, 1,
                null, null, null,
                $broodEggs, null, $broodHatched,
                $showCount, $showScore,
                $this->requester['id']
            );

        } else { // layers
            query\Result::new(
                $pair['id'], $pair['breederId'], $pair['districtId'], $pair['year'],
                $pair['group'], $pair['breedId'], $pair['colorId'],
                1, 1,
                $pair['dames'], $pair['lay']['production'], $pair['lay']['weight'],
                $broodEggs, $broodFertile, $broodHatched,
                $showCount, $showScore,
                $this->requester['id']
            );

        }
    }
}
