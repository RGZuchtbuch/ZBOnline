<?php

namespace App\controller;

use App\model;
use App\model\Query;
use App\model\Requester;
use HttpRequestException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpNotImplementedException;
use Slim\Exception\HttpUnauthorizedException;

class Pair
{
	// get the whole thing
	public static function get( Request $request, Response $response, array $args ) : Response {
		$id = $args[ 'id' ] ?? null;
		if( is_numeric( $id ) ) {
			$pair = model\Pair::get( $id );
			if( $pair ) {
				//$requester = new Requester( $request );
				//if( $requester && ( $requester->isAdmin() || $requester->isModerating( $pair[ 'districtId' ] ) || $requester->hasId( $pair[ 'breederId' ] ) ) ) {
				$pair['breed']   = model\Breed::get( $pair['breedId'] );
				$pair['color']   = model\Color::get( $pair['colorId'] );

				$pair['breeder'] = model\Breeder::getName($pair['breederId']);
					$pair['parents'] = model\Pair::getParents($pair['id']);
					foreach( $pair['parents'] as & $parent ) {
						//$brood['chicks'] = model\Pair::getChicks( $brood['id'] );
						$parent['parentsPair'] = $parent['parentsPairId'] !== null ? model\pair::getPairResult( $parent['parentsPairId'] ) : null;
					}
					$pair['lay']     = model\Pair::getLay($pair['id']);
					$pair['broods']  = model\Pair::getBroods( $pair['id'] );
					foreach( $pair['broods'] as & $brood ) {
						$brood['chicks'] = model\Pair::getChicks( $brood['id'] );
					}
					$pair['show'] = self::toShow( model\Pair::getShow( $pair['id'] ) );

					$response->getBody()->write(json_encode([ 'pair' => $pair ], JSON_UNESCAPED_SLASHES));
					return $response;
				//}
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
                if( $id &&
                    Pair::postParents( $id, $pair[ 'parents' ] ?? null, $requester ) &&
                    Pair::postLay( $id, $pair['lay'], $requester ) &&
                    Pair::postBroods( $id, $pair['broods'] ?? null, $requester ) &&
                    Pair::postShow($id, $pair['show'] ?? null, $requester ) &&
                    Pair::postResult( $id, $pair, $requester )
                ) {
					Query::commit();
					model\Cache::del('result' ); // clear cache as results changed
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
					if( model\Pair::del( $id ) && model\Pair::delParents( $id ) && model\Pair::delLay( $id ) && model\Pair::delBroods( $id ) && model\Pair::delShow( $id ) && model\Result::delForPair( $id ) ) {
						Query::commit();
						model\Cache::del('result' ); // clear cache as results changed
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
			$success = model\Pair::delBroods($pairId) && model\Pair::delChicks($pairId); // remove old broods and chicks
			foreach ($broods as & $brood) {
				if ($brood['eggs'] && $brood['hatched']) {
					$broodId = model\Pair::newBrood($pairId, $brood['start'], $brood['eggs'], $brood['fertile'], $brood['hatched'], $requester->getId());
					$success = $success && $broodId;
					if ($broodId) {
						$chicks = $brood['chicks'] ?? null;
						if( $chicks ) { // null for layers empty or filled for pigeons
                            foreach ($brood['chicks'] as $chick) {
                                if ($chick['ring']) {
                                    $success = $success && model\Pair::newChick($pairId, $broodId, $brood['ringed'], $chick['ring'], $requester->getId());
                                }
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
			return
				model\Pair::delShow($pairId) &&
				model\Pair::newShow($pairId, $show['89'], $show['90'], $show['91'], $show['92'], $show['93'], $show['94'], $show['95'], $show['96'], $show['97'], $requester->getId());
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
		} else { // being 0, so none counted
            $showCount = null;
        }

		// save pigeon or layer
		if( $pair['sectionId'] === 5 ) { // pigeon, no lay, no color
            $success = $success && model\Result::new(
				$pairId, $pair['districtId'], $pair['year'], $pair['group'],
				null, $pair['breedId'], null, null,
				1, 1,
				null, null, null,
				$broodEggs, null, $broodHatched,
				$showCount, $showScore,
                $requester->getId()
			);
		} else { // layers
            $success = $success && model\Result::new(
				$pairId, $pair['districtId'], $pair['year'], $pair['group'],
				null, $pair['breedId'], $pair['colorId'], null,
				1, 1,
				$pair['lay']['dames'],	$pair['lay']['production'],	$pair['lay']['weight'],
				$broodEggs, $broodFertile, $broodHatched,
				$showCount, $showScore,
                $requester->getId()
			);

		}
		return $success;
	}

	public static function filter( Request $request, Response $response, array $args ) : Response {
		//$requester = new Requester( $request );
		$query      = $request->getQueryParams();
		$breederId  = $query[ 'breederId' ] ?? null;
		$districtId = $query[ 'districtId' ] ?? null;
		$year       = $query[ 'year' ] ?? null;

		if( is_numeric( $breederId ) && is_numeric( $year ) ) { // for breeders and some year
			//$pairs = model\Pair::getPairsInYear( $breederId, $year );
			$pairs = self::toPairs( model\Pair::getPairsInYear( $breederId, $year ) );
			$response->getBody()->write( json_encode( [ 'pairs'=>$pairs ], JSON_UNESCAPED_SLASHES));
			return $response;
		} else if( is_numeric( $districtId ) ) { // for district, TODO should be for a year
			//if( $requester->isAdmin() || $requester->isModerating( $districtId ) ) { //admin of the moderator or self
				$pairs = model\District::getPairs( $districtId );
				$response->getBody()->write(json_encode( [ 'pairs' => $pairs ], JSON_UNESCAPED_SLASHES));
				return $response;
			//}
			throw new HttpUnauthorizedException( $request, 'Cannot do this' );
		} else if( is_numeric( $breederId ) ) { // for breeder, TODO should not be used, only for year ?
			$breeder = model\Breeder::get($breederId);
			if( $breeder ) {
				$districtId = $breeder['districtId'] ?? null;
				//if ($requester->isAdmin() || $requester->isModerating($districtId) || $requester->hasId($breederId)) { //admin of the moderator or self
					$pairs = model\Breeder::getPairs($breederId);
					$response->getBody()->write(json_encode(['pairs' => $pairs], JSON_UNESCAPED_SLASHES));
					return $response;
				//}
				throw new HttpUnauthorizedException($request, 'Cannot do this');
			}
			throw new HttpNotFoundException( $request, 'Breeder unknown' );
		}
		throw new HttpBadRequestException( $request, 'Invalid query');
	}


	// helpers
	public static function toPairs( array $rows ) : array {
		$pairs = [];
		foreach( $rows as $row ) {
			$pair          = [ 'id'=>$row['id'], 'breederId'=>$row['breederId'], 'year'=>$row['year'], 'name'=>$row['name'] ];
			$pair['breeder'] = [ 'id'=>$row['breederId'] ]; // TODO add name but dep on Pair::getPairs
			$pair['lay']   = [ 'eggs'=>$row['layEggs'], 'weight'=>$row['layWeight'], 'eggsShould'=>$row['layEggsShould'], 'weightShould'=>$row['layWeightShould'] ];
			$pair['brood'] = [ 'eggs'=>$row['broodEggs'], 'fertile'=>$row['broodFertile'], 'hatched'=>$row['broodHatched'], 'group'=>$row['broodGroup'] ];
			$pair['show']  = [ 'count'=>$row['showCount'], 'score'=>$row['showScore'] ];
			$pairs[] = $pair;
		}
		return $pairs;
	}

	public static function toShow( array $raw ) : array {
		//$sum = $raw['89']*89+$raw['90']*90+$raw['91']*91+$raw['92']*92+$raw['93']*93+$raw['94']*94+$raw['95']*95+$raw['96']*96+$raw['97']*97;
		//$total = $raw['89']+$raw['90']+$raw['91']+$raw['92']+$raw['93']+$raw['94']+$raw['95']+$raw['96']+$raw['97'];
		//$avg = $total > 0 ? $sum / $total : null;
		$score = [ 'id'=>$raw['id'], 'pairId'=>$raw['pairId'],
			'scores'=>[
				[ 'count'=>$raw['89'], 'weight'=>89 ],
				[ 'count'=>$raw['90'], 'weight'=>90 ],
				[ 'count'=>$raw['91'], 'weight'=>91 ],
				[ 'count'=>$raw['92'], 'weight'=>92 ],
				[ 'count'=>$raw['93'], 'weight'=>93 ],
				[ 'count'=>$raw['94'], 'weight'=>94 ],
				[ 'count'=>$raw['95'], 'weight'=>95 ],
				[ 'count'=>$raw['96'], 'weight'=>96 ],
				[ 'count'=>$raw['97'], 'weight'=>97 ],
			],
			//'avg'=>$avg,
		];
		return $score;
	}
}
