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
		Logger::log( $requester, $request, "Create district" );
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
		Logger::log( $requester, $request, "Update district" );
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
		Logger::log( $requester, $request, "Delete district" );
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
