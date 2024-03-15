<?php

namespace App\controller;

use App\model;
use App\model\Query;
use App\model\Requester;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;

class Pair
{
	// get the whole thing
	public static function get( Request $request, Response $response, array $args ) : Response {
		$id = $args[ 'id' ] ?? null;
		if( is_numeric( $id ) ) {
			$pair = model\Pair::get( $id );
			if( $pair ) {
				$requester = new Requester( $request );
				if( $requester && ( $requester->isAdmin() || $requester->isModerating( $pair[ 'districtId' ] ) || $requester->hasId( $pair[ 'breederId' ] ) ) ) {
					$pair['breeder'] = model\Breeder::getName($pair['breederId']);
					$pair['parents']  = model\Pair::getParents($pair['id']);
					$pair['lay']     = model\Pair::getLay($pair['id']);
					$pair['broods']  = model\Pair::getBroods( $pair['id'] );
					foreach( $pair['broods'] as & $brood ) {
						$brood['chicks'] = model\Pair::getChicks( $brood['id'] );
					}
					$pair['show'] = model\Pair::getShow( $pair['id'] );
					$response->getBody()->write(json_encode([ 'pair' => $pair ], JSON_UNESCAPED_SLASHES));
					return $response;
				}
				throw new HttpUnauthorizedException( $request, 'No way' );
			}
			throw new HttpNotFoundException( $request, 'Pair not found' );
		}
		throw new HttpBadRequestException( $request, 'Bad id' );
	}

	// only pair
	public static function post( Request $request, Response $response, array $args ) : Response
	{
		$id = $args['id'] ?? null; // case of put existing
		$pair = $request->getParsedBody();
		if ($pair) {
			$requester = new Requester($request);
			if ( $requester && ($requester->isAdmin() || $requester->isModerating($pair['districtId']) || $requester->hasId($pair['breederId']) ) ) {
				Query::begin();
                $id = Pair::postPair( $id, $pair, $requester );
//				if ($id && Pair::postParents( $id, $pair[ 'parents' ], $requester ) && Pair::postLay( $id, $pair['lay'], $requester ) && Pair::postBroods( $id, $pair['broods'], $requester ) && Pair::postShow($id, $pair['show'], $requester ) && Pair::postResult( $id, $pair, $requester ))  {
//                $success = $id && Pair::postParents( $id, $pair[ 'parents' ], $requester );
//                if( $success ) {
//                $pair['lay']['dames'] = $pair['dames']; // redundant but handy
//				$lay = $pair['lay'] ?? null;
                if( $id &&
                    Pair::postParents( $id, $pair[ 'parents' ] ?? null, $requester ) &&
                    Pair::postLay( $id, $pair['lay'], $requester ) &&
                    Pair::postBroods( $id, $pair['broods'] ?? null, $requester ) &&
                    Pair::postShow($id, $pair['show'] ?? null, $requester ) &&
                    Pair::postResult( $id, $pair, $requester )
                ) {
					Query::commit();
					$response->getBody()->write( json_encode([ 'id' => $id ], JSON_UNESCAPED_SLASHES) );
					return $response;
				} else {
					Query::rollback();
					throw new HttpUnauthorizedException( $request, 'Cannot do this');
				}
			}
			throw new HttpUnauthorizedException( $request, 'Cannot do this');
		}
		throw new HttpBadRequestException( $request, 'Missing body' );
	}

	// no put

	// delete the whole struct
	public static function delete( Request $request, Response $response, array $args ) : Response
	{
		$id = $args['id'] ?? null;
		if (is_numeric($id)) {
			$pair = model\Pair::get( $id );
			if( $pair ) {
				$requester = new Requester($request);
				if ($requester && ($requester->isAdmin() || $requester->isModerating($pair['districtId']) || $requester->hasId($pair['breederId']))) {
					Query::begin();
					if( model\Pair::delParents( $id ) && model\Pair::delLay( $id ) && model\Pair::delBroods( $id ) && model\Pair::delShow( $id ) && model\Pair::del( $id ) ) {
						Query::commit();
						$response->getBody()->write(json_encode(['success' => true ], JSON_UNESCAPED_SLASHES));
						return $response;
					} else {
						Query::rollback(); // recover
						throw new HttpInternalServerErrorException( $request, 'Oops, DB trouble here' );
					}
				}
				throw new HttpUnauthorizedException($request, 'Cannot do this');
			}
			throw new HttpNotFoundException( $request, 'unused id' );
		}
		throw new HttpBadRequestException($request, 'Missing arg id');
	}


	/** helpers **/

	public static function postPair( ? int $id, array $body, Requester $requester ) : int {
		if( $id == null ) { //
			return model\Pair::new($body['breederId'], $body['districtId'], $body['year'], $body['group'], $body['sectionId'], $body['breedId'], $body['colorId'], $body['name'], $body['paired'], $body['notes'], $requester->getId());
		} else {
			$success = model\Pair::set($body['id'], $body['breederId'], $body['districtId'], $body['year'], $body['group'], $body['sectionId'], $body['breedId'], $body['colorId'], $body['name'], $body['paired'], $body['notes'], $requester->getId());
			if( $success ) {
				return $id;
			}
			return false;
		}
	}

	public static function postParents( int $pairId, array $parents, Requester $requester ) : bool
	{
		if( $parents ) {
			$success = model\Pair::delParents($pairId); // remove existing parent links to replace by new once
			foreach ($parents as & $parent) {
				if ($parent['ring']) {
					// note pairId is this parents parents and parentPair is the pair this parent is parent. to be consistent with pair_child
					$success = $success && model\Pair::newParent($pairId, $parent['sex'], $parent['ring'], $parent['score'], $parent['parentsPairId'], $requester->getId());
				}
			}
			return $success;
		}
		return true; // no parents is ok
	}

	public static function postLay( int $pairId, ? array $lay, Requester $requester ) : bool {
        if( $lay && $lay['start'] && $lay['end'] && $lay['eggs'] ) {
            return model\Pair::delLay($pairId) && model\Pair::newLay($pairId, $lay['start'], $lay['end'], $lay['eggs'], $lay['dames'], $lay['weight'], $requester->getId());
        }
        return true; // no lay is ok
	}

	public static function postBroods( int $pairId, array $broods, Requester $requester ) : bool {
		if( $broods ) {
			$success = model\Pair::delBroods($pairId) && model\Pair::delChicks($pairId); // remove broods and chicks
			foreach ($broods as & $brood) {
				if ($brood['eggs'] && $brood['hatched']) {
					$broodId = model\Pair::newBrood($pairId, $brood['start'], $brood['eggs'], $brood['fertile'], $brood['hatched'], $requester->getId());
					$success = $success && $broodId;
					if ($broodId) {
						foreach ($brood['chicks'] as $chick) {
							if ($chick['ring']) {
								$success = $success && model\Pair::newChick($pairId, $broodId, $brood['ringed'], $chick['ring'], $requester->getId());
							}
						}
					}
				}
			}
			return $success; // all is well
		}
		return true; // no broods is ok
	}

	public static function postShow( int $pairId, array $show, Requester $requester ) : bool {
		if( $show ) {
			return model\Pair::delShow($pairId) && model\Pair::newShow($pairId, $show['89'], $show['90'], $show['91'], $show['92'], $show['93'], $show['94'], $show['95'], $show['96'], $show['97'], $requester->getId());
		}
		return true; // no show is ok
	}

	public static function postResult( int $pairId, array $pair, Requester $requester ) : bool {
		$success = model\Result::delForPair( $pairId );

		// summarize broods
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

		// summerize show
		$show = & $pair['show'];
		$showCount = $show['89']+$show['90']+$show['91']+$show['92']+$show['93']+$show['94']+$show['95']+$show['96']+$show['97']; // null or count if any
		$showScore = null;
		if( $showCount > 0 ) {
			$showTotalScore = 89 * $show['89'] + 90 * $show['90'] + 91 * $show['91'] + 92 * $show['92'] + 93 * $show['93'] + 94 * $show['94'] + 95 * $show['95'] + 96 * $show['96'] + 97 * $show['97'];
			$showScore = $showTotalScore / $showCount;
		}

		// save pigeon or layer
		if( $pair['sectionId'] === 5 ) { // pigeon, no lay, no color
            $success = $success && model\Result::new(
				$pairId, $pair['districtId'], $pair['year'],
				$pair['group'], $pair['breedId'], null,
				1, 1,
				null, null, null,
				$broodEggs, null, $broodHatched,
				$showCount, $showScore,
                $requester->getId()
			);

		} else { // layers
            $success = $success && model\Result::new(
				$pairId, $pair['districtId'], $pair['year'],
				$pair['group'], $pair['breedId'], $pair['colorId'],
				1, 1,
				$pair['dames'],
				$pair['lay']['production'],
				$pair['lay']['weight'],
				$broodEggs, $broodFertile, $broodHatched,
				$showCount, $showScore,
                $requester->getId()
			);

		}
		return $success;
	}


}