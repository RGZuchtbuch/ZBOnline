<?php

namespace App\controller;

use App\model;
use App\model\Requester;
use App\util\Logger;
use App\util\ToolBox;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;

class Federation
{

	public static function get( Request $request, Response $response, array $args ) : Response {
		$id = $args[ 'id' ] ?? null;
		if( $id ) {
			if( is_numeric( $id ) ) {
				$district = model\District::read($id);
				if ( $district ) {
					$response->getBody()->write(json_encode(['district' => $district], JSON_UNESCAPED_SLASHES));
					return $response;
				}
				throw new HttpNotFoundException($request, 'District not found');
			}
		}
		throw new HttpBadRequestException( $request, 'Bad id' );
	}

	public static function post( Request $request, Response $response, array $args ) : Response {
		$requester = new Requester( $request );
		if( $requester->isAdmin() ) {
			$body = $request->getParsedBody();
			if( $body ) {
				model\Cache::delete('district' );
				model\Cache::delete('result' );
				model\Cache::delete('report' );
				$id = model\District::create( $body['parentId'], $body['name'], $body['fullname'], $body['short'], $body['url'], $body['latitude'], $body['longitude'], $body['level'], $body['moderatorId'], $requester->getId() );
				if( $id ) {
					$response->getBody()->write(json_encode(['id' => $id], JSON_UNESCAPED_SLASHES));
					return $response;
				}
				throw new HttpInternalServerErrorException( $request, 'Oops, error creating new district' );
			}
			throw new HttpBadRequestException( $request, 'Missing body' );
		}
		throw new HttpUnauthorizedException( $request, 'Cannot do this');
	}

	public static function put( Request $request, Response $response, array $args ) : Response {
		$requester = new Requester( $request );
		if( $requester->isAdmin() ) {
			$id = $args[ 'id' ] ?? null;
			$body = $request->getParsedBody();
			if( is_numeric( $id ) && $body ) {
				model\Cache::delete('district' );
				model\Cache::delete('result' );
				model\Cache::delete('report' );
				$updated = model\District::update($body['id'], $body['name'], $body['fullname'], $body['short'], $body['url'], $body['latitude'], $body['longitude'], $body['level'], $body['moderatorId'], $requester->getId() );
				if( $updated ) {
					$response->getBody()->write(json_encode(['id' => $id], JSON_UNESCAPED_SLASHES));
					return $response;
				}
				throw new HttpNotFoundException( $request, 'Cannot update');
			}
			throw new HttpBadRequestException( $request, 'Bad id or body');
		}
		throw new HttpUnauthorizedException( $request, 'not Admin');
	}

	public static function delete( Request $request, Response $response, array $args ) : Response {
		$requester = new Requester( $request );
		if( $requester->isAdmin() ) {
			$id = $args[ 'id' ] ?? null;
			if( $id && is_numeric( $id ) ) {
				model\Cache::delete('district' );
				model\Cache::delete('result' );
				model\Cache::delete('report' );
				$deleted = model\District::delete( $id );
				if( $deleted ) {
					$response->getBody()->write(json_encode([ 'id'=>$id, 'success'=>true ], JSON_UNESCAPED_SLASHES));
					return $response;
				}
				throw new HttpNotFoundException( $request, 'Cannot delete');
			}
			throw new HttpBadRequestException( $request, 'Cannot delete');
		}
		throw new HttpUnauthorizedException( $request, 'not Admin');
	}

// ****************************************

	// should be in breeder
//	public static function breeders( Request $request, Response $response, array $args ) : Response {
//		$id = $args[ 'id' ];
//		if( is_numeric( $id ) ) {
//			$breeders = model\District::getBreeders( $id );
//			$response->getBody()->write(json_encode(['breeders' => $breeders], JSON_UNESCAPED_SLASHES));
//			return $response;
//		}
//		throw new HttpBadRequestException( $request, 'Bad id' );
//	}

	// filter with parent:parentId
//	public static function children(Request $request, Response $response, array $args ) : Response {
//		$id = $args[ 'id' ];
//		if( is_numeric( $id ) ) {
//			$children = model\District::children( $id );
//			$response->getBody()->write(json_encode(['children' => $children], JSON_UNESCAPED_SLASHES));
//			return $response;
//		}
//		throw new HttpBadRequestException( $request, 'Bad id' );
//	}

	// filter with root:rootId
//	public static function descendants( Request $request, Response $response, array $args ) : Response {
//		$id = $args[ 'id' ];
//		if( is_numeric( $id ) ) {
//			$districts = model\District::descendants($id); // get all districts including root
//			if( $districts ) {
//				foreach( $districts as & $district ) {
//					$district['moderator'] = $district[ 'moderatorId' ] ? model\User::get($district['moderatorId']) : null;
//				}
//				$rootDistrict = ToolBox::toTree($districts);
//				if ($rootDistrict) {
//					$response->getBody()->write(json_encode(['district' => & $rootDistrict], JSON_UNESCAPED_SLASHES));
//					return $response;
//				}
//				throw new HttpInternalServerErrorException($request, 'No root district... wierd, please inform admin');
//			}
//			throw new HttpNotFoundException($request, 'root district not found');
//		}
//		throw new HttpBadRequestException( $request, 'Bad id' );
//	}

//    // for results edit list for section showing unopened breeds
//	// should be with result with filter district:districtId, year:year, section:sectionId. group:groupId
//	public static function results( Request $request, Response $response, array $args ) : Response {
//		$id = ToolBox::toInt( $args[ 'id' ] ); // district
//		$query = $request->getQueryParams();
//		$year = ToolBox::toInt( $query[ 'year' ] ?? null );
//		$sectionId = ToolBox::toInt( $query[ 'section' ] ?? null );
//		$group = $query[ 'group' ] ?? null;
//		if( $id && $year && $sectionId && $group ) { // all not null and > 0 as all id's should
//			if( $sectionId === 9999 ) { // aoc klasse
//				$results = model\District::getAocResults( $id, $year, $group );
//			} else {
//				$results = model\District::getSectionResults( $id, $sectionId, $year, $group );
//			}
//			$response->getBody()->write(json_encode( [ 'results' => & $results ], JSON_UNESCAPED_SLASHES));
//			return $response;
//		}
//		throw new HttpBadRequestException( $request, 'Bad arguments values' );
//	}

    // for results edit list when opening breed
	// should be in result
//	public static function breedResults( Request $request, Response $response, array $args ) : Response {
//		$id = ToolBox::toInt( $args[ 'id' ] );
//		$breedId = ToolBox::toInt($args[ 'breed' ] ?? null );
//		$query = $request->getQueryParams();
//			$year = ToolBox::toInt($query[ 'year' ] ?? null );
//			$sectionId = ToolBox::toInt($query[ 'section' ] ?? null );
//			$group = $query[ 'group' ] ?? null;
//		if( $id && $year && $sectionId && $breedId && $group ) { // all not null and > 0 or filled string
//			$results = $sectionId == 5 ? // == as sectionId is text
//				model\District::getBreedResult($id, $breedId, $year, $group) :
//				model\District::getColorResults($id, $breedId, $year, $group);
//			$response->getBody()->write(json_encode( [ 'results' => & $results ], JSON_UNESCAPED_SLASHES));
//			return $response;
//		}
//		throw new HttpBadRequestException( $request, 'Bad arguments values' );
//	}

	// returns section/subsection/breed/color tree results for generating table
	// should be in report
//	public static function report( Request $request, Response $response, array $args ) : Response {
//		Logger::add( null, $request );
//
//		$json = model\Cache::get( 'Result', $request->getUri()->getPath(), $request->getUri()->getQuery() );
//        if( $json ) { // in cache
//            $response->getBody()->write( $json );
//            return $response;
//        }
//		$id = $args[ 'id' ];
//		$year = $args[ 'year' ];
//		if( is_numeric( $id ) && is_numeric( $year ) ) {
//			$results = model\Result::getResultsDistrictYear( $id, $year );
//			$report = ToolBox::toReportTree( $results );
//			if( $report ) {
//                $json = json_encode( [ 'report' => & $report ], JSON_UNESCAPED_SLASHES);
//				$response->getBody()->write( $json );
//                model\Cache::set( 'Result', $request->getUri()->getPath(), $request->getUri()->getQuery(), $json );
//				return $response;
//			}
//			throw new HttpInternalServerErrorException($request, 'No root district... wierd, please inform admin');
//		}
//		throw new HttpBadRequestException( $request, 'Bad id or year' );
//	}

	// new for v3
	// get children of a parent { parentId:1 }
	// get descendants of root, with root { rootId:1
	public static function filter( Request $request, Response $response, array $args ) : Response {
		//$requester = new Requester( $request );
		$query     = $request->getQueryParams();
		if( $query ) {
			$parentId    = $query['parentId'] ?? null; // for children
			$rootId      = $query['rootId'] ?? null; // for descendants
			$moderatorId = $query['moderatorId'] ?? null;

			if (is_numeric($parentId)) { // children as parentId
				$children = model\District::children($parentId);
				$response->getBody()->write(json_encode(['children' => $children], JSON_UNESCAPED_SLASHES));
				return $response;
			} elseif (is_numeric($rootId)) { // descendants from rootId, like for whole fed.
				$json = model\Cache::get( 'district', $request->getUri()->getPath(), $request->getUri()->getQuery() );
				if( $json ) { // in cache
					$response->getBody()->write( $json );
					return $response;
				}
				// not in cache yet
				$descendants = model\District::descendants($rootId); // get all districts including root
				if ($descendants) {
					foreach ($descendants as & $district) { // get moderator for each district
						$district['moderator'] = $district['moderatorId'] ? model\User::get($district['moderatorId']) : null;
					}
					$rootDistrict = ToolBox::toTree($descendants);
					if ($rootDistrict) {
						$json = json_encode(['root' => & $rootDistrict], JSON_UNESCAPED_SLASHES);
						$response->getBody()->write( $json );
						model\Cache::set( 'district', $request->getUri()->getPath(), $request->getUri()->getQuery(), $json );
						return $response;
					}
					throw new HttpInternalServerErrorException($request, 'No root district... wierd, please inform admin');
				}
				throw new HttpNotFoundException($request, 'root district not found');
			}
			throw new HttpBadRequestException($request, 'Bad rootId');
		} else {
			$districts = model\District::all(); // get all
			$response->getBody()->write(json_encode( ['districts' => $districts ], JSON_UNESCAPED_SLASHES));
			return $response;
		}
		// cache should be on class, url and querystring?
		//$json = model\Cache::get( 'Result', $request->getUri()->getPath(), $request->getUri()->getQuery() );
		//if( $json ) { // in cache
		//	$response->getBody()->write( $json );
		//	return $response;
		//}
	}

}
