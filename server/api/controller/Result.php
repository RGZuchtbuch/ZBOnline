<?php

namespace App\controller;

use App\model;
use App\model\Requester;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;

class Result
{
	public static function read(Request $request, Response $response, array $args ) : Response {
		$id = $args[ 'id' ] ?? null;
		if( $id ) { // specific result
			if( is_numeric( $id ) ) {
				$result = model\Result::get( $id );
				if( $result ) {
					$response->getBody()->write(json_encode([ 'result' => $result ], JSON_UNESCAPED_SLASHES));
					return $response;
				}
				throw new HttpNotFoundException($request, 'Result not found');
			}
			throw new HttpBadRequestException( $request, 'Bad id' );
		}
		throw new HttpBadRequestException( $request, 'Cannot get all results, too many' );
		// else could get all results but that's bogus
	}


	public static function post( Request $request, Response $response, array $args ) : Response
	{
		$result = $request->getParsedBody();
		if ($result) {
			$requester = new Requester($request);
			if ($requester && ($requester->isAdmin() || $requester->isModerating($result['districtId']))) { //granted
				//model\Cache::del('result' ); // clear cache as results changed
				model\Cache::del('report' ); // clear cache as results changed
				$id = model\Result::new(
					$result['pairId'], $result['districtId'], $result['year'], $result['group'],
					$result['sectionId'], $result['breedId'], $result['colorId'], $result['aocColor'],
					$result['breeders'], $result['pairs'],
					$result['lay']['dames'], $result['lay']['eggs'], $result['lay']['weight'],
					$result['brood']['eggs'], $result['brood']['fertile'], $result['brood']['hatched'],
					$result['show']['count'], $result['show']['score'], $requester->getId()
				);
				if ($id) {
					$response->getBody()->write(json_encode([ 'created'=>true, 'id' => $id], JSON_UNESCAPED_SLASHES));
					return $response;
				}
				throw new HttpInternalServerErrorException($request, 'Error creating new breed');
			}
			throw new HttpUnauthorizedException($request, 'Cannot do this');
		}
		throw  new HttpBadRequestException( $request, 'Missing body' );
	}

	public static function put( Request $request, Response $response, array $args ) : Response
	{
		$id = $args[ 'id' ] ?? null;
		if( is_numeric( $id ) && $id > 0 ) {
			$result = $request->getParsedBody();
			if ($result) {
				$requester = new Requester($request);
				if ($requester && ($requester->isAdmin() || $requester->isModerating($result['districtId']))) { //granted
					//model\Cache::del('result' ); // clear cache as results changed
					model\Cache::del('report' ); // clear cache as results changed
					$updated = model\Result::set( // change
						$result['id'],
						$result['pairId'], $result['districtId'], $result['year'], $result['group'],
						$result['sectionId'], $result['breedId'], $result['colorId'], $result['aocColor'],
						$result['breeders'], $result['pairs'],
						$result['lay']['dames'], $result['lay']['eggs'], $result['lay']['weight'],
						$result['brood']['eggs'], $result['brood']['fertile'], $result['brood']['hatched'],
						$result['show']['count'], $result['show']['score'],
						$requester->getId()
					);
					if( $updated ) {
						$response->getBody()->write(json_encode([ 'updated' => true, 'id' => (int)$id ], JSON_UNESCAPED_SLASHES));
						return $response;
					}
					throw new HttpInternalServerErrorException($request, 'Error updating result');
				}
				throw new HttpUnauthorizedException($request, 'Cannot do this');
			}
			throw  new HttpBadRequestException($request, 'Missing body');
		}
		throw  new HttpBadRequestException($request, 'Bad id');
	}

	public static function delete( Request $request, Response $response, array $args ) : Response
	{
		$id = $args[ 'id' ] ?? null;
		if( $id && $id > 0 ) {
			$result = model\Result::get( $id ); // needs to exist and for checking authorization!
			if( $result ) {
				$requester = new Requester($request);
				if ($requester && ($requester->isAdmin() || $requester->isModerating($result['districtId']))) { //granted
					//model\Cache::del('result' ); // clear cache as results changed
					model\Cache::del('report' ); // clear cache as results changed
					$deleted = model\Result::delete( $id );
					if( $deleted ) {
						$response->getBody()->write(json_encode(['deleted' => true, 'id' => $id], JSON_UNESCAPED_SLASHES));
						return $response;
					}
					throw new HttpInternalServerErrorException($request, 'Error updating result');
				}
				throw new HttpUnauthorizedException($request, 'Cannot do this');
			}
			throw  new HttpBadRequestException($request, 'Result not found');
		}
		throw  new HttpBadRequestException($request, 'Bad id');
	}

	/** other getters **/

    // getting one result for bar chart for a district and a year
//	public static function resultFor( Request $request, Response $response, array $args ) : Response // TODO ever used ?
//	{
//        $json = model\Cache::get( 'Result', $request->getUri()->getPath(), $request->getUri()->getQuery() );
//        if( $json ) { // in cache
//            $response->getBody()->write( $json );
//            return $response;
//        }
//        $districtId     = $args['districtId'] ?? null;
//        $year           = $args['year'] ?? null;
//
//        $query          = $request->getQueryParams();
//        $sectionId  = $query[ 'section' ] ?? null;
//        $breedId    = $query[ 'breed' ] ?? null;
//        $colorId    = $query[ 'color' ] ?? null;
//        $group      = $query[ 'group' ] ?? null;
//
//        if( $districtId && $year && $districtId>0 && $year>0 ) {
//            $result = model\Result::getResultDistrictYear( $districtId, $year, $sectionId, $breedId, $colorId, $group );
//            if( $result ) {
//                $json = json_encode([ 'result' => $result ], JSON_UNESCAPED_SLASHES);
//                $response->getBody()->write( $json );
//                model\Cache::set( 'Result', $request->getUri()->getPath(), $request->getUri()->getQuery(), $json );
//                return $response;
//            }
//            throw new HttpNotFoundException($request, 'Result not found');
//        }
//        throw  new HttpBadRequestException($request, 'Bad arguments');
//	}

    // for trend
//	public static function years( Request $request, Response $response, array $args ) : Response
//	{
//        $json = model\Cache::get( 'Result', $request->getUri()->getPath(), $request->getUri()->getQuery() );
//        if( $json ) { // in cache
//            $response->getBody()->write( $json );
//            return $response;
//        }
//        $districtId     = $args['id'] ?? null;
//
//        $query          = $request->getQueryParams();
//            $sectionId  = $query[ 'section' ] ?? null;
//            $breedId    = $query[ 'breed' ] ?? null;
//            $colorId    = $query[ 'color' ] ?? null;
//            $group      = $query[ 'group' ] ?? null;
//
//        if( $districtId && $districtId>0 ) {
//
//            $years = model\Result::getResultsDistrictYears( $districtId, $sectionId, $breedId, $colorId, $group );
//            $json = json_encode([ 'years' => $years ], JSON_UNESCAPED_SLASHES);
//            $response->getBody()->write( $json );
//            model\Cache::set( 'Result', $request->getUri()->getPath(), $request->getUri()->getQuery(), $json );
//            return $response;
//        }
//        throw  new HttpBadRequestException($request, 'Bad arguments');
//
//	}

    // for map
//	public static function districts( Request $request, Response $response, array $args ) : Response
//	{
//        $json = model\Cache::get( 'Result', $request->getUri()->getPath(), $request->getUri()->getQuery() );
//        if( $json ) { // in cache
//            $response->getBody()->write( $json );
//            return $response;
//        }
//		$year       = $args['year'] ?? null;
//
//		$query          = $request->getQueryParams(); // may all be null meaning *
//			$sectionId  = $query[ 'section' ] ?? null;
//			$breedId    = $query[ 'breed' ] ?? null;
//			$colorId    = $query[ 'color' ] ?? null;
//			$group      = $query[ 'group' ] ?? null;
//
//		if( $year && $year > 0 ) {
//			$districts = model\Result::getResultsYearDistricts( $year, $sectionId, $breedId, $colorId, $group );
//            $json = json_encode([ 'districts' => $districts ], JSON_UNESCAPED_SLASHES);
//			$response->getBody()->write( $json );
//            model\Cache::set( 'Result', $request->getUri()->getPath(), $request->getUri()->getQuery(), $json );
//            return $response;
//		}
//		throw  new HttpBadRequestException($request, 'Bad arguments');
//	}


	// new approach 2
	public static function filter( Request $request, Response $response, array $args ) : Response {
		$query = $request->getQueryParams();
		$districtId = $query['district'] ?? null;
		$sectionId  = $query['section'] ?? null;
		$breedId    = $query['breed'] ?? null;
		$year       = $query['year'] ?? null;
		$group      = $query['group'] ?? null;
		//$colorId = $query['color'] ?? null;

		// no cache yet, needed ?

		if( is_numeric( $districtId ) && is_numeric( $sectionId ) && is_numeric( $year ) && $group ) { //for edit
			// TODO, next still valid as now aoc are not in separate section anymore
			if( $sectionId == 9999 ) { // for editing aoc's
				$results = self::formatResults( model\Result::forAocColors( $districtId, $year, $group ) );
			} else { // breedlist for editings results
				$results = model\Result::forSectionBreeds( $districtId, $sectionId, $year, $group );
			}
			$response->getBody()->write(json_encode( [ 'results'=>&$results, 'section'=>$sectionId ], JSON_UNESCAPED_SLASHES));
			return $response;
		}

		else if( is_numeric( $districtId ) && is_numeric($breedId) && is_numeric( $year ) && $group ) { // per breed edit
			// for editing opened breeds, so breed for pigeons and colors for layers
			$breedResult = model\Result::forBreedResult( $districtId, $breedId, $year, $group );
			$breedResult = self::formatResult( $breedResult );
			$colorResults = model\Result::forColorsResult($districtId, $breedId, $year, $group );
			$colorResults = self::formatResults( $colorResults );
			$aocColorResults = model\Result::forAocColorsResult($districtId, $breedId, $year, $group );
			$aocColorResults = self::formatResults( $aocColorResults );
			$response->getBody()->write(json_encode( [ 'results' => [ 'breed'=>$breedResult, 'colors'=>$colorResults, 'aocColors'=>$aocColorResults, 'query' => $query ] ], JSON_UNESCAPED_SLASHES));
			return $response;
		}

		else if( is_numeric( $districtId ) && is_numeric( $year ) ) { // per district and year view like for moderater
			$results = model\Result::forDistrictYear($districtId, $year);
//			print( "ooooooooooooooo\n");
//			print_r( $results );
//			print( "-----------\n");
			$tree = self::treeResults($results);
//			print( "uuuuuuuuuuuuuuuuuu\n");
//			print_r( $tree );
//			print( "-----------\n");
			$response->getBody()->write(json_encode(['results' => $tree], JSON_UNESCAPED_SLASHES));
			return $response;
		}
		throw new HttpBadRequestException( $request, 'Bad filter' );
	}

	private static function formatResults( array $results ) : array {
		$out = [];
		foreach( $results as $result) {
			$out[] = self::formatResult( $result );
		}
		return $out;
	}

	public static function formatResult( array & $raw ) : array	{
		return [
			'id'          => $raw['id'],
			'pairId'	  => $raw['pairId'],
			'districtId'  =>$raw['districtId'],
			'year'        => $raw['year'],
			'group'       =>$raw['group'],
			'sectionId'   => $raw['sectionId'], 'breedId'=>$raw['breedId'],
			'colorId'     =>$raw['colorId'], 'colorName'=>$raw['colorName'], 'aocColor'=>$raw['aocColor'],
			'breeders'    => $raw['breeders'], 'pairs'=>$raw['pairs'],
			'lay'         => [ 'dames'=>$raw['layDames'], 'eggs'=>$raw['layEggs'], 'weight'=>$raw['layWeight'] ],
			'brood'       => [ 'eggs'=>$raw['broodEggs'], 'fertile'=>$raw['broodFertile'], 'hatched'=>$raw['broodHatched'] ],
			'show'        => [ 'count'=>$raw['showCount'], 'score'=>$raw['showScore'] ],
		];
	}

	public static function treeResults( array $results) : array {
		$tree = [ 'sections'=>[] ];
		$section = [ 'id'=>NULL ];
		$breed = [ 'id'=>NULL ];
		foreach( $results as & $raw ) {
			//print( $raw['id'].PHP_EOL );
//			unset( $result );
			$result = [
				'id'=>$raw['id'], 'accepted'=>$raw['accepted'],
				'districtId'=>$raw['districtId'], 'year'=>$raw['year'], 'group'=>$raw['group'],
				'rootSectionId'=>$raw['rootsectionId'], 'sectionId' => $raw['sectionId'], 'breedId'=>$raw['breedId'], 'colorId'=>$raw['colorId'], 'aocColor'=>$raw['aocColor'],
				'breeders'=>$raw['breeders'], 'pairs'=>$raw['pairs'],
				'lay'     =>[ 'dames'=>$raw['layDames'], 'eggs'=>$raw['layEggs'], 'weight'=>$raw['layWeight'] ],
				'brood'   =>[ 'eggs'=>$raw['broodEggs'], 'fertile'=>$raw['broodFertile'], 'hatched'=>$raw['broodHatched'] ],
				'show'    =>[ 'count'=>$raw['showCount'], 'score'=>$raw['showScore'] ],
				'pairId'=>$raw['pairId'],
				'breeder' => $raw['breederId'] === NULL ? NULL : [ 'id'=>$raw['breederId'], 'firstname'=>$raw['firstname'], 'infix'=>$raw['infix'], 'lastname'=>$raw['lastname'], 'short'=>$raw['short'] ],
			];
			if( $raw['rootsectionId'] !== $section['id'] ) {
				unset( $section ); // unbind from tree
				$section = [ 'id'=>$raw['rootsectionId'], 'name'=>$raw['rootsectionname'], 'breeds'=>[] ];
				$tree['sections'][] = & $section;
			}
			if( $raw['breedId'] !== $breed['id'] || ( $raw['colorId'] === NULL && $raw['aocColor'] === NULL ) ) {
//			if( true ) { // causes double breed entry
				unset( $breed ); // unbind from tree
				$breed = [ 'id'=>$raw['breedId'], 'name'=>$raw['breedname'], 'colors'=>[] ];
				if( $raw['colorId'] === NULL && $raw['aocColor'] === NULL ) $breed['result'] = $result; // if no color, like pigeons
				$section['breeds'][] = & $breed;
			}
			if( $raw['colorId'] !== NULL || $raw['aocColor'] !== NULL) {
				unset( $color ); // unbind from tree
				$color =  [	'id'=>$raw['colorId'], 'name'=>$raw['colorId'] === NULL ? $raw['aocColor'] : $raw['colorname'], 'result'=> $result ];
				$breed['colors'][] = & $color;
			}
		}
		unset( $section, $breed, $color );
		return $tree;
	}


}