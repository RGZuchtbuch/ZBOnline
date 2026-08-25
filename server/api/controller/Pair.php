<?php

namespace App\controller;

use App\model;
use App\model\Requester;
use App\util\Logger;
use App\util\Query;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\NullLogger;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;

class Pair
{
	// get the whole thing
	public static function read(Request $request, Response $response, array $args ) : Response {
		$requester = new Requester( $request );
		Logger::log( $requester, $request, "Read pair" );
		$id = $args[ 'id' ] ?? null;
		if( is_numeric( $id ) ) {
			$pair = model\Pair::read( $id ); // get pair main data
			if( $pair ) {
				//$requester = new Requester( $request );
				if( $requester && ( $requester->isAdmin() || $requester->isModerating( $pair[ 'districtId' ] ) || $requester->hasId( $pair[ 'breederId' ] ) ) ) {
					$pair['breed']   = model\std\Breed::get( $pair['breedId'] );
					$pair['color']   = model\std\Color::get( $pair['colorId'] );

					$pair['breeder'] = model\Breeder::read( $pair['breederId'] );

					$pair['parents'] = model\Animal::readParentsForPair( $pair['id'] );
					// get pairs parents results
					foreach( $pair['parents'] as & $parent ) {
						//$brood['chicks'] = model\Pair::getChicks( $brood['id'] );
						$parent['parentsPair'] = $parent['parentsPairId'] !== null ? model\Result::readForPair( $parent['parentsPairId'] ) : null;
					}

					$pair['lay'] = model\pair\Lay::readForPair( $pair['id'] );
					// adjusting for counting or averaging lay result
					if( $pair['lay'] ) { // deduct average and eggs from lay data
						if( $pair['lay']['start'] === null ) { // no period, so avg given
							$pair['lay']['average'] = $pair['lay']['eggs'];
							$pair['lay']['eggs'] = null;
						} else {
							$pair['lay']['average'] = null;
						}
					}

					$pair['broods']  = model\pair\Brood::readForPair( $pair['id'] );
					foreach( $pair['broods'] as & $brood ) {
						$pair['debug'][] = $brood['id'];
						$brood['chicks'] = [];
						$chicks = model\Animal::readForBrood( $brood['id'] );
						foreach( $chicks as $chick ) {
							$brood['chicks'][] = $chick['ring'];
						}
						if( count( $chicks ) > 0 ) {
							$brood['ringed'] = $chicks[0]['ringed'];
						}
					}

					$pair['show'] = self::toShow( model\pair\Show::readForPair( $pair['id'] ) );
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
		$requester = new Requester($request);
		Logger::log( $requester, $request, "Create pair" );
		return Pair::store( $requester, $request, $response, $args );

//		$id = $args['id'] ?? null; // case of put existing
//		$pair = $request->getParsedBody();
//		if ($pair) {
//			if ( $requester && ($requester->isAdmin() || $requester->isModerating($pair['districtId']) || $requester->hasId($pair['breederId']) ) ) {
//				Query::begin();
//                $id = Pair::postPair( $id, $pair, $requester );
//                if( $id &&
//                    Pair::postParents( $id, $pair[ 'parents' ] ?? null, $requester ) &&
//                    Pair::postLay( $id, $pair['lay'], $requester ) &&
//                    Pair::postBroods( $id, $pair['broods'] ?? null, $requester ) &&
//                    Pair::postShow($id, $pair['show'] ?? null, $requester ) &&
//                    Pair::postResult( $id, $pair, $requester )
//                ) {
//					Query::commit();
//					model\Cache::delete('result' ); // clear cache as results changed
//					model\Cache::delete('report' ); // clear cache as results changed
//					$response->getBody()->write( json_encode([ 'id' => $id ], JSON_UNESCAPED_SLASHES) );
//					return $response;
//				} else {
//					Query::rollback();
//					throw new HttpUnauthorizedException( $request, 'Cannot do this');
//				}
//			}
//			throw new HttpUnauthorizedException( $request, 'Cannot do this');
//		}
//		throw new HttpBadRequestException( $request, 'Missing body' );
	}

	// put same as post as all data is replaced for the pair
	public static function put( Request $request, Response $response, array $args ) : Response	{
		$requester = new Requester($request);
		Logger::log( $requester, $request, "Update pair" );
		return Pair::store( $requester, $request, $response, $args );
	}

	public static function store( Requester $requester, Request $request, Response $response, array $args ) : Response
	{
		$id = $args['id'] ?? null; // case of put existing
		$pair = $request->getParsedBody();
		if ($pair) {
			if ( $requester && ($requester->isAdmin() || $requester->isModerating($pair['districtId']) || $requester->hasId($pair['breederId']) ) ) {
				Query::begin();
                $id = Pair::postPair( $id, $pair, $requester );
                if(
					$id &&
                    Pair::postParents( $id, $pair[ 'parents' ] ?? null, $requester ) &&
                    Pair::postLay( $id, $pair['lay'], $requester ) &&
                    Pair::postBroods( $id, $pair['broods'] ?? null, $requester ) &&
                    Pair::postShow($id, $pair['show'] ?? null, $requester ) &&
                    Pair::postResult( $id, $pair, $requester )
                ) {
					Query::commit();
					model\Cache::delete('result' ); // clear cache as results changed
					model\Cache::delete('report' ); // clear cache as results changed
					$response->getBody()->write( json_encode([ 'id' => $id ], JSON_UNESCAPED_SLASHES) );
					return $response;
				} else {
					Query::rollback();
					throw new HttpUnauthorizedException( $request, 'Cannot do this due to post problem');
				}
			}
			throw new HttpUnauthorizedException( $request, 'Cannot do this');
		}
		throw new HttpBadRequestException( $request, 'Missing body' );
	}


	// delete the whole struct
	public static function delete( Request $request, Response $response, array $args ) : Response
	{
		$requester = new Requester($request);
		Logger::log( $requester, $request, "Deleting pair" );
		$id = $args['id'] ?? null;
		if (is_numeric($id)) {
			$pair = model\Pair::read( $id );
			if( $pair ) {
				if ($requester && ($requester->isAdmin() || $requester->isModerating($pair['districtId']) || $requester->hasId($pair['breederId']))) {
					Query::begin();
					model\Cache::delete('result' ); // clear cache as results changed
					model\Cache::delete('report' ); // clear cache as results changed
					if(
						model\Pair::delete( $id ) &&
						model\Animal::deleteParentsForPair( $id ) &&
						model\pair\Lay::deleteForPair( $id ) &&
						model\pair\Brood::deleteForPair( $id ) &&
						model\Animal::deleteChicksForPair( $id ) &&
						model\pair\Show::deleteForPair( $id ) &&
						model\Result::deleteForpair( $id )
					) {
						Query::commit();
						model\Cache::delete('result' ); // clear cache as results changed
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

	// create or update pair
	public static function postPair( ? int $id, array $body, Requester $requester ) : int {
		if( ! $requester->isAdmin() && ! $requester->isModerating( $body[ 'districtId' ] ) ) {
			$body['accepted'] = 0; // can only be set by mod
		}
		if( $id == null ) { //
			return model\Pair::create( $body['breederId'], $body['districtId'], $body['year'], $body['group'], $body['sectionId'], $body['breedId'], $body['colorId'], $body['name'], $body['paired'], $body['notes'], $body['accepted'], $requester->getId());
		} else {
			$success = model\Pair::update( $body['id'], $body['breederId'], $body['districtId'], $body['year'], $body['group'], $body['sectionId'], $body['breedId'], $body['colorId'], $body['name'], $body['paired'], $body['notes'], $body['accepted'], $requester->getId());
			if( $success ) {
				return $id;
			}
			return false;
		}
	}

	public static function postParents( int $pairId, array $parents, Requester $requester ) : bool {
		model\Animal::deleteParentsForPair( $pairId );
		if( $parents ) { // add if any
			$success = true;
			foreach ($parents as & $parent) {
				if( $parent['ring'] ) { // valid if has ring
					$success = $success && model\Animal::createParent($pairId, $parent['sex'], $parent['ring'], $parent['score'], $parent['parentsPairId'], $requester->getId());
				}
			}
			return $success;
		}
		return true; // no parents is ok
	}

	public static function postLay( int $pairId, ? array $lay, Requester $requester ) : bool {
		model\pair\Lay::deleteForPair( $pairId );
		if( $lay ) {
			if( $lay['average'] ?? null ) { // valid avg, same as $lay['result'], result stored as eggs
				return model\pair\Lay::create( $pairId, null, null, null, $lay['average'], $lay['weight'], $requester->getId() );
			} else if( $lay['start'] && $lay['end'] && $lay['dames'] && $lay['eggs'] ) { // valid period, now counted eggs stored as eggs
				return model\pair\Lay::create( $pairId, $lay['start'], $lay['end'], $lay['dames'], $lay['eggs'], $lay['weight'], $requester->getId() );
			}
		}
//        if( $lay && $lay['start'] && $lay['end'] && $lay['dames'] && $lay['eggs'] ) { // valid
//			return model\pair\Lay::create( $pairId, $lay['start'], $lay['end'], $lay['dames'], $lay['eggs'], $lay['weight'], $requester->getId() );
//        }
        return true; // no lay is ok
	}

	public static function postBroods( int $pairId, array $broods, Requester $requester ) : bool {
		model\Animal::deleteChicksForPair( $pairId ); // replacing all
		model\pair\Brood::deleteForPair( $pairId ); // resplacing all
		if( $broods ) {
			$success = true; // model\Pair::delBroods($pairId) && model\Pair::delChicks($pairId); // remove old broods and chicks
			foreach ($broods as & $brood) {
				if ($brood['eggs'] > 0 && $brood['hatched'] !== null) { // valid
					$brood['id'] = model\pair\Brood::create( $pairId, $brood['start'], $brood['eggs'], $brood['fertile'], $brood['hatched'], $requester->getId() );
					$success = $success && $brood['id'] >= 0;

					if( $brood['id'] ) {
						$success = $success && Pair::postChicks($pairId, $brood, $requester);
					}
				} else if( $brood['id'] > 0 ) { // if existing but invalid, delete
					model\pair\Brood::delete( $brood['id'] );
				}
			}
			return $success; // all is well
		}
		return true; // no broods is ok
	}

	public static function postChicks( $pairId, $brood, Requester $requester ) : bool {
		// chicks for pair should already be deleted in postBroods
		if( $pairId && $brood['id'] && $brood['chicks'] ) {
			$success = true;
			foreach ($brood['chicks'] as & $chick) {
				if( $chick ) { // valid
					$id = model\Animal::createChick( $pairId, $brood['id'], $brood['ringed'] ?? null, $chick, $requester->getId() );
					$success = $success && $id;
				}
			}
			return $success;
		}
		return true;
	}

	public static function postShow( int $pairId, array $show, Requester $requester ) : bool {
		model\pair\Show::deleteForPair( $pairId );
		if( $show && $show['scores'] ) {
			$scores = & $show['scores'];
			$id = model\pair\Show::create( $pairId, $scores['89'], $scores['90'], $scores['91'], $scores['92'], $scores['93'], $scores['94'], $scores['95'], $scores['96'], $scores['97'], $requester->getId() );
			//print( $id.'\n' ); // TODO
			return $id;
		}
		return true; // no show is ok
	}

	public static function postResult( int $pairId, array $pair, Requester $requester ) : bool
	{
		$success = model\Result::deleteForpair($pairId);

		//if( $pair['accepted'] ) { // only add result if moderated accepted the pair

			// summarize broods
			$broods = & $pair['broods']; // at least empty array
			$broodEggs = null;
			$broodFertile = null;
			$broodHatched = null;
			foreach ($broods as & $brood) {
				if ($brood['eggs'] !== null && $brood['eggs'] > 0 && $brood['hatched'] !== null && $brood['hatched'] >= 0) {
					$broodEggs += $brood['eggs'];
					$broodHatched += $brood['hatched'];
					if ($brood['fertile'] !== null && $brood['fertile'] >= 0) {
						$broodFertile += $brood['fertile'];
					}
				}
			}

			// summerize show
			$show = & $pair['show']; // can't be null...
			$scores = & $show['scores'];

			$showCount = 0;
			$showTotal = 0;
			$showScore = null;
			foreach ($scores as $points => $count) { // key = pounts, value = amount
				$showCount = $showCount + $count;
				$showTotal = $showTotal + $count * $points;
			}

			if ($showCount > 0) { // avoid div by zero
				$showScore = $showTotal / $showCount;
			} else {
				$showCount = null;
			}

			// save pigeon or layer
			if ($pair['sectionId'] === 5) { // pigeon, no lay, no color
				$success = $success && model\Result::new(
						$pairId, null, $pair['districtId'], $pair['year'], $pair['group'],
						null, $pair['breedId'], $pair['colorId'], null,
						1, 1,
						null, null, null,
						$broodEggs, null, $broodHatched,
						$showCount, $showScore,
						$requester->getId()
					);
			} else { // layers
				if ($pair && $pair['lay']['average']) { // avg provided for goose etc that do not lay year though
					$layResult = $pair['lay']['average'];
				} else {
					$layResult = $pair['lay']['result'];//
				}
				$success = $success && model\Result::new(
					$pairId, null, $pair['districtId'], $pair['year'], $pair['group'],
					null, $pair['breedId'], $pair['colorId'], null,
					1, 1,
					$pair['lay']['dames'], $layResult, $pair['lay']['weight'],
					$broodEggs, $broodFertile, $broodHatched,
					$showCount, $showScore,
					$requester->getId()
				);
			}
		//}
		return $success;
	}

	// NO cache used here
	public static function filter( Request $request, Response $response, array $args ) : Response {
		$requester = new Requester( $request );
		$query      = $request->getQueryParams();
		$breederId  = $query[ 'breeder' ] ?? null;
		$breedId    = $query[ 'breed' ] ?? null;
		$chickRing  = $query[ 'chick' ] ?? null; // for finding parentPair
		$sectionId  = $query[ 'section' ] ?? null;
		$districtId = $query[ 'district' ] ?? null;
		$year       = $query[ 'year' ] ?? null;

//		$json = model\Cache::get( 'pairs', $request->getUri()->getPath(), $request->getUri()->getQuery() );
//		if( $json ) { // in cache
//			$response->getBody()->write( $json );
//			return $response;
//		}

		if( is_numeric( $breederId ) && is_numeric( $breedId ) && is_numeric( $year ) ) { // for parent pairs in year
			$breeder = model\Breeder::read( $breederId );
			if(
				( $breeder && $requester->hasId( $breeder['id'] ) ) ||
				$requester->isModerating( $breeder['districtId'] ) ||
				$requester->isAdmin()
			) { //admin of the moderator or self
				$pairs = Pair::toPairs( model\Pair::readForBreederBreedYear($breederId, $breedId, $year) );
				$json = json_encode(['pairs' => $pairs], JSON_UNESCAPED_SLASHES);
				$response->getBody()->write( $json );
				return $response;
			}
			throw new HttpUnauthorizedException( $request, 'Cannot do this' );
		} elseif( is_numeric( $breederId ) ) { // all breeders pairs
			$breeder = model\Breeder::read( $breederId ); // need breeder info for auth
			if(
				( $breeder && $requester->hasId( $breeder['id'] ) ) ||
				$requester->isModerating( $breeder['districtId'] ) ||
				$requester->isAdmin()
			) { //admin of the moderator or self
				$pairs = Pair::toPairs( model\Pair::readForBreeder($breederId) );
				$response->getBody()->write(json_encode(['pairs' => $pairs], JSON_UNESCAPED_SLASHES));
				return $response;
			}
			throw new HttpUnauthorizedException( $request, 'Cannot do this' );
		} elseif( is_numeric( $districtId ) && is_numeric( $year ) ) { // for district in year, assuming only used by results per pair
			if(
				$requester->isModerating( $districtId ) ||
				$requester->isAdmin()
			) { //admin of the moderator or self
				$breeders = Pair::toBreedersPairsTree( model\Pair::readForDistrictInYear( $districtId, $year ) );
				$response->getBody()->write(json_encode( [ 'pairs' => $breeders ], JSON_UNESCAPED_SLASHES));
				return $response;
			}
			throw new HttpUnauthorizedException( $request, 'Cannot do this' );
//		} elseif( is_numeric( $districtId ) && is_numeric( $year ) ) { // for district in year, assuming only used by results per pair
//			if(
//				$requester->isModerating( $districtId ) ||
//				$requester->isAdmin()
//			) { //admin of the moderator or self
//				$pairs = []; //model\Pair::readForDistrictInYear( $districtId, $year );
//				$response->getBody()->write(json_encode( [ 'pairs' => $pairs ], JSON_UNESCAPED_SLASHES));
//				return $response;
//			}
//			throw new HttpUnauthorizedException( $request, 'Cannot do this' );
		} elseif( $chickRing ) { // find pair to chick, only breeder, meod and admin
			$pair = model\Pair::findForChick( $chickRing );
			if( $pair ) { // chicks parentPair found
				$breeder = model\Breeder::read( $pair['breederId'] ); // need breeder
				if( // authorized
					$requester->hasId( $pair['breederId'] ) || // breeder
					$requester->isModerating( $breeder['districtId'] ) || // moderator
					$requester->isAdmin() // admin
				) {
					$pair = Pair::toParentPair( $pair ); // row to hierarchy
					$response->getBody()->write(json_encode( [ 'pairs' => [ $pair ] ], JSON_UNESCAPED_SLASHES));
					return $response;
				} else {
					throw new HttpUnauthorizedException( $request, 'Cannot do this' );
				}
			} // no parentPair found, a valid outcome
			$response->getBody()->write(json_encode( [ 'pairs' => null ], JSON_UNESCAPED_SLASHES));
			return $response;
		}
		throw new HttpBadRequestException( $request, 'Invalid query');
	}


	// helpers
	public static function toPairs( array $rows ) : array {
		$pairs = [];
		foreach( $rows as $row ) {
			$pair            = [ 'id'=>$row['id'], 'breederId'=>$row['breederId'], 'year'=>$row['year'], 'name'=>$row['name'], 'accepted'=>$row['accepted'] ];
			$pair['breeder'] = [ 'id'=>$row['breederId'] ]; // TODO add name but dep on Pair::getPairs
			$pair['section'] = [ 'sectionId'=>$row['sectionId'] ];
			$pair['breed']   = [ 'id'=>$row['breedId'], 'name'=>$row['breedName'] ];
			$pair['color']   = [ 'id'=>$row['colorId'], 'name'=>$row['colorName'] ];
			$pair['lay']     = [ 'eggs'=>$row['layEggs'], 'weight'=>$row['layWeight'], 'eggsShould'=>$row['layEggsShould'], 'weightShould'=>$row['layWeightShould'] ];
			$pair['brood']   = [ 'eggs'=>$row['broodEggs'], 'fertile'=>$row['broodFertile'], 'hatched'=>$row['broodHatched'], 'group'=>$row['broodGroup'] ];
			$pair['show']    = [ 'count'=>$row['showCount'], 'score'=>$row['showScore'] ];
			$pairs[] = $pair;
		}
		return $pairs;
	}

	public static function toParentPair( $row ) {
		$pair            = [ 'id'=>$row['id'], 'year'=>$row['year'], 'name'=>$row['name'], 'accepted'=>$row['accepted'] ];
		$pair['lay']     = [ 'eggs'=>$row['layEggs'], 'weight'=>$row['layWeight'], 'eggsShould'=>$row['layEggsShould'], 'weightShould'=>$row['layWeightShould'] ];
		$pair['brood']   = [ 'eggs'=>$row['broodEggs'], 'fertile'=>$row['broodFertile'], 'hatched'=>$row['broodHatched'], 'group'=>$row['broodGroup'] ];
		$pair['show']    = [ 'count'=>$row['showCount'], 'score'=>$row['showScore'] ];
		return $pair;
	}


	public static function toShow( array $raw ) : array {
		//$sum = $raw['89']*89+$raw['90']*90+$raw['91']*91+$raw['92']*92+$raw['93']*93+$raw['94']*94+$raw['95']*95+$raw['96']*96+$raw['97']*97;
		//$total = $raw['89']+$raw['90']+$raw['91']+$raw['92']+$raw['93']+$raw['94']+$raw['95']+$raw['96']+$raw['97'];
		//$avg = $total > 0 ? $sum / $total : null;
		$score = [ 'id'=>$raw['id'], 'pairId'=>$raw['pairId'], 'scores'=>[] ];
		foreach ( [ 89,90,91,92,93,94,95,96,97 ] as $key ) {
			$score['scores'][ $key ] = $raw[ $key ];
		}
		return $score;
	}
	public static function toShowOld( array $raw ) : array {
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

	public static function toBreedersPairsTree( array $rows ) : array {
		$breeders = [];
		$breederId = 0;
		$breeder = [ 'id'=> 0 ];
		foreach( $rows as $row ) {
			if( $row[ 'breederId' ] !== $breederId ) {
				$breederId = $row[ 'breederId' ];
				unset( $breeder );
				$breeder = [ 'id'=>$row['breederId'], 'districtId'=>$row['districtId'], 'member'=>$row['member'], 'firstname'=>$row['firstname'], 'infix'=>$row['infix'], 'lastname'=>$row['lastname'], 'pairs'=>[] ];
				$breeders[] = & $breeder;
			}
			if( $row['id'] !== null ) {
				$breeder[ 'pairs' ][] = [
					'id'=>$row['id'], 'year'=>$row['year'], 'group'=>$row['group'], 'name'=>$row['name'],
					'sectionId'=>$row['sectionId'], 'breedId'=>$row['breedId'], 'colorId'=>$row['colorId'],
					'lay'=> [
						'eggs'=>$row['layEggs'], 'weight'=>$row['layWeight']
					],
					'brood' => [
						'eggs'=>$row['broodEggs'], 'fertile'=>$row['broodFertile'], 'hatched'=>$row['broodHatched']
					],
					'show' => [
						'count'=>$row['showCount'], 'score'=>$row['showScore']
					]
				];
			}
		}
		return $breeders;
	}
}
