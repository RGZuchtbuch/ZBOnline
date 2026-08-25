<?php

namespace App\controller;

use App\model;
use App\model\Requester;
use App\util\Logger;
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
	}

	public static function post( Request $request, Response $response, array $args ) : Response
	{
		$requester = new Requester($request);
		Logger::log( $requester, $request, "Create result" );
		$result = $request->getParsedBody();
		if ($result) {
			if ($requester && ($requester->isAdmin() || $requester->isModerating($result['districtId']))) { //granted
				//model\Cache::del('result' ); // clear cache as results changed
				model\Cache::delete('report' ); // clear cache as results changed
				$id = model\Result::new(
					$result['pairId'] ?? null, $result['breedingId'] ?? null, $result['districtId'], $result['year'], $result['group'],
					$result['sectionId'] ?? null, $result['breedId'], $result['colorId'] ?? null, $result['aocColor'] ?? null,
					$result['breeders'], $result['pairs'] ?? null,
					$result['lay']['dames'] ?? null, $result['lay']['eggs'] ?? null, $result['lay']['weight'] ?? null,
					$result['brood']['eggs'] ?? null, $result['brood']['fertile'] ?? null, $result['brood']['hatched'] ?? null,
					$result['show']['count'] ?? null, $result['show']['score'] ?? null,
					$requester->getId()
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
		$requester = new Requester($request);
		Logger::log( $requester, $request, "Update result" );
		$id = $args[ 'id' ] ?? null;
		if( is_numeric( $id ) && $id > 0 ) {
			$result = $request->getParsedBody();
			if ($result) {
				if ($requester && ($requester->isAdmin() || $requester->isModerating($result['districtId']))) { //granted
					//model\Cache::del('result' ); // clear cache as results changed
					model\Cache::delete('report' ); // clear cache as results changed
					$updated = model\Result::set( // change
						$result['id'],
						$result['pairId'] ?? null, $result['breedingId'] ?? null, $result['districtId'], $result['year'], $result['group'],
						$result['sectionId'] ?? null, $result['breedId'], $result['colorId'] ?? null, $result['aocColor'] ?? null,
						$result['breeders'], $result['pairs'] ?? null,
						$result['lay']['dames'] ?? null, $result['lay']['eggs'] ?? null, $result['lay']['weight'] ?? null,
						$result['brood']['eggs'] ?? null, $result['brood']['fertile'] ?? null, $result['brood']['hatched'] ?? null,
						$result['show']['count'] ?? null, $result['show']['score'] ?? null,
						$requester->getId()
					);
					if( $updated ) {
						$response->getBody()->write(json_encode([ 'updated' => $updated, 'id' => (int)$id ], JSON_UNESCAPED_SLASHES));
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
		$requester = new Requester($request);
		Logger::log( $requester, $request, "Delete result" );
		$id = $args[ 'id' ] ?? null;
		if( $id && $id > 0 ) {
			$result = model\Result::get( $id ); // needs to exist and for checking authorization!
			if( $result ) {
				if ($requester && ($requester->isAdmin() || $requester->isModerating($result['districtId']))) { //granted
					//model\Cache::del('result' ); // clear cache as results changed
					model\Cache::delete('report' ); // clear cache as results changed
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
	public static function filter( Request $request, Response $response, array $args ) : Response {
		$requester = new Requester( $request );
		Logger::log( $requester, $request, "Result" );

		$query = $request->getQueryParams();
		$districtId = $query['district'] ?? null;
		$sectionId  = $query['section'] ?? null;
		$breedId    = $query['breed'] ?? null;
		$year       = $query['year'] ?? null;
		$group      = $query['group'] ?? null;
		//$colorId = $query['color'] ?? null;

		// no cache yet, needed ?

		if( ( $requester && ( $requester->isAdmin() || $requester->isModerating( $districtId ) ) ) && is_numeric( $districtId ) && is_numeric( $sectionId ) && is_numeric( $year ) && $group ) { //for edit
			// TODO, next still valid as now aoc are not in separate section anymore
			if( $sectionId == 9999 ) { // for editing aoc's
				$results = self::formatResults( model\Result::forDistrictAocs( $districtId, $year, $group ) );
			} else { // breedlist for editings results
				$results = model\Result::forSectionBreeds( $districtId, $sectionId, $year, $group );
			}
			$response->getBody()->write(json_encode( [ 'results'=>&$results, 'section'=>$sectionId ], JSON_UNESCAPED_SLASHES));
			return $response;
		}

		else if( is_numeric( $districtId ) && is_numeric($breedId) && is_numeric( $year ) && $group ) { // per breed edit
			// for editing opened breeds, so breed for pigeons and colors for layers
			$breedResult = model\Result::forDistrictBreedResult( $districtId, $breedId, $year, $group );
			$breedResult = self::formatResult( $breedResult );
			$colorResults = model\Result::forDistrictColorsResult($districtId, $breedId, $year, $group );
			$colorResults = self::formatResults( $colorResults );
			$aocColorResults = model\Result::forDistrictAocsResult($districtId, $breedId, $year, $group );
			$aocColorResults = self::formatResults( $aocColorResults );
			$response->getBody()->write(json_encode( [ 'results' => [ 'breed'=>$breedResult, 'colors'=>$colorResults, 'aocColors'=>$aocColorResults, 'query' => $query ] ], JSON_UNESCAPED_SLASHES));
			return $response;
		}

		else if( is_numeric( $districtId ) && is_numeric( $year ) ) { // per district and year, moderator results view
			$results = model\Result::forDistrictYear($districtId, $year);
			$tree = self::toDistrictYearTree($results);
			$response->getBody()->write(json_encode(['results' => $tree], JSON_UNESCAPED_SLASHES));
			return $response;
		}
		throw new HttpBadRequestException( $request, 'Bad filter' );
	}

	public static function getBreedersResults(Request $request, Response $response, array $args ) : Response {
		$query = $request->getQueryParams();
		$districtId = $query[ 'district' ] ?? null;
		$year       = $query[ 'year' ] ?? null;

		if( is_numeric( $districtId ) && is_numeric( $year ) ) {
			$results = model\Result::forDistrictBreeders( $districtId, $year );
			$breeders = self::toBreedersResultsTree( $results );
			$response->getBody()->write( json_encode( [ 'results' => $breeders ], JSON_UNESCAPED_SLASHES ) );
			return $response;
		}
		throw new HttpBadRequestException( $request, 'Bad arguments' );
	}




/** private helpers **/
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

	public static function toDistrictYearTree(array $results) : array {
		$tree = [ 'sections'=>[] ];
		$section = [ 'id'=>NULL ];
		$breed = [ 'id'=>NULL ];
		foreach( $results as & $raw ) {
			//print( $raw['id'].PHP_EOL );
//			unset( $result );
			$result = [
				'id'=>$raw['id'], 'accepted'=>$raw['accepted'],
				'pairId'=>$raw['pairId'], 'breedingId'=>$raw['breedingId'], 'districtId'=>$raw['districtId'], 'year'=>$raw['year'], 'group'=>$raw['group'],
				'rootSectionId'=>$raw['rootsectionId'], 'sectionId' => $raw['sectionId'], 'breedId'=>$raw['breedId'], 'colorId'=>$raw['colorId'], 'aocColor'=>$raw['aocColor'],
				'breeders'=>$raw['breeders'], 'pairs'=>$raw['pairs'],
				'lay'     =>[ 'dames'=>$raw['layDames'], 'eggs'=>$raw['layEggs'], 'weight'=>$raw['layWeight'] ],
				'brood'   =>[ 'eggs'=>$raw['broodEggs'], 'fertile'=>$raw['broodFertile'], 'hatched'=>$raw['broodHatched'] ],
				'show'    =>[ 'count'=>$raw['showCount'], 'score'=>$raw['showScore'] ],

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

	public static function toBreedersResultsTree( array $rows ) : array {
		$breeders = [];
		$breederId = 0;
		$breeder = [ 'id'=> 0 ];
		foreach( $rows as $row ) {
			if( $row[ 'breederId' ] !== $breederId ) {
				$breederId = $row[ 'breederId' ];
				unset( $breeder );
				$breeder = [ 'id'=>$row['breederId'], 'districtId'=>$row['districtId'], 'member'=>$row['member'], 'firstname'=>$row['firstname'], 'infix'=>$row['infix'], 'lastname'=>$row['lastname'], 'results'=>[] ];
				$breeders[] = & $breeder;
			}
			if( $row['resultId'] !== null ) {
				$breeder[ 'results' ][] = [
					'id'=>$row['resultId'], 'breederId'=>$row['breederId'], 'pairId'=>$row['pairId'], 'breedingId'=>$row['breedingId'], 'districtId'=>$row['districtId'], 'year'=>$row['year'], 'group'=>$row['group'],
					'sectionId'=>$row['sectionId'], 'breedId'=>$row['breedId'], 'colorId'=>$row['colorId'],
					'breeders'=>$row['breeders'], 'pairs'=>$row['pairs'],
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