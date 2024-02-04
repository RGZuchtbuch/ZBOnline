<?php

namespace App\controller\pair;

use App\controller\Controller;
use App\model;
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
        model\Cache::del( 'results' );

        model\Query::begin(); // begin transaction
        try {
            model\Cache::del('pairs'); // clear cache
            model\Cache::del('results');
            $pair = $this->data;
            $id = $pair['id'] ?? null;
            if ($id) {
                model\Pair::set($pair['id'], $pair['breederId'], $pair['districtId'], $pair['year'], $pair['group'], $pair['sectionId'], $pair['breedId'], $pair['colorId'], $pair['name'], $pair['paired'], $pair['notes'], $this->requester['id']);
            } else { // id being 0 for new
                $id = model\Pair::new($pair['breederId'], $pair['districtId'], $pair['year'], $pair['group'], $pair['sectionId'], $pair['breedId'], $pair['colorId'], $pair['name'], $pair['paired'], $pair['notes'], $this->requester['id']);
                $pair['id'] = $id; // continue with new id
            }
            // TODO for all parts...
            $this->updateElders($pair);
            $this->updateLay($pair);
            $this->updateBroods($pair);
            $this->updateShow($pair);

            $this->updateResult( $pair );

            model\Query::commit();
            return ['id' => $id];
        } catch( PDOException $e ) {
            model\Query::rollback();
            throw $e; //new HttpInternalServerErrorException( $this->request, $e->getTraceAsString().'<br><br><br>'.$e->getMessage() );
        }
        //return ['id' => null];
    }

    private function updateElders( & $pair ) {
        model\Elder::delForPair( $pair['id'] );
        $elders = $pair['elders'];
        if( $elders ) {
            foreach( $elders as & $elder ) {
                if( $elder['sex'] && $elder['ring'] ) {
                    model\Elder::new( $pair['id'], $elder['sex'], $elder['ring'], $elder['score'], $elder['pair'], $this->requester['id'] );
                }
            }
        }

    }

    private function updateLay( & $pair ) {
        model\Lay::delForPair( $pair['id'] );
        $lay = $pair[ 'lay' ];
        if( $pair['sectionId'] !== 5 && $lay && $lay['start'] && $lay['end'] && $lay[ 'eggs' ] ) { // only if valid
            model\Lay::new($pair['id'], $lay['start'], $lay['end'], $lay['eggs'], $lay['dames'], $lay['weight'], $this->requester['id'] );
        }
    }

    private function updateBroods( & $pair ) {
        model\Chick::delForPair( $pair['id'] ); // remove all for pair to reinsert
        model\Brood::delForPair( $pair['id'] ); // idem

        if( isset( $pair['broods'] ) ) {
            $broods = & $pair['broods'];

            foreach( $broods as & $brood ) {
                if( $pair['sectionId'] === 5 ) { // pigeons
                    $brood['eggs'] = 2;
                    $brood['fertile'] = null;
                    if( $brood['hatched'] !== null && $brood['hatched'] >= 0 && $brood['hatched'] <= $brood['eggs'] ) {
                        $brood['id'] = model\Brood::new( $pair['id'], $brood['start'], $brood['eggs'], $brood['fertile'], $brood['hatched'], $this->requester['id'] );
                    }
                } else { // layers
                    if( $brood['eggs'] !== null && $brood['eggs'] > 0 && $brood['hatched'] !== null && $brood['hatched'] >=0 &&
                        ( $brood['hatched'] <= ( $brood['fertile'] !== null && $brood['fertile'] >= 0 ?  $brood['fertile'] :  $brood['eggs'] ) ) ) {
//                  if( $brood['eggs'] != null && $brood['hatched'] != null && ( $brood['hatched'] <= ( $brood['fertile'] != null ? $brood['fertile'] : $brood['eggs'] ) ) ) {

                        $brood['id'] = model\Brood::new( $pair['id'], $brood['start'], $brood['eggs'], $brood['fertile'], $brood['hatched'], $this->requester['id'] );
                    }
                }

                foreach( $brood['chicks'] as & $chick ) { // save all chicks ( 2 x pigeon )
                    if( $chick['ring'] ) {
                        model\Chick::new($pair['id'], $brood['id'], $chick['ring'], $brood['ringed'], $this->requester['id']);
                    }
                }
            }
        }
    }


    private function updateShow( & $pair ) {
        model\Show::delForPair( $pair['id'] );
        $show = $pair[ 'show' ] ?? null;
        if( $show ) { // only if valid
            model\Show::new($pair['id'], $show['89'], $show['90'], $show['91'], $show['92'], $show['93'], $show['94'], $show['95'], $show['96'], $show['97'], $this->requester['id'] );
        }
    }

    private function updateResult( & $pair ) {
        model\Result::delForPair( $pair['id'] );

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
            model\Result::new(
                $pair['id'], $pair['districtId'], $pair['year'],
                $pair['group'], $pair['breedId'], null,
                1, 1,
                null, null, null,
                $broodEggs, null, $broodHatched,
                $showCount, $showScore,
                $this->requester['id']
            );

        } else { // layers
            model\Result::new(
                $pair['id'], $pair['districtId'], $pair['year'],
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
