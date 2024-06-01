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

class District
{

	public static function get( Request $request, Response $response, array $args ) : Response {
		$id = $args[ 'id' ] ?? null;
		if( $id ) {
			if( is_numeric( $id ) ) {
				$district = model\District::get($id);
				if ( $district ) {
					$response->getBody()->write(json_encode(['district' => $district], JSON_UNESCAPED_SLASHES));
					return $response;
				}
				throw new HttpNotFoundException($request, 'District not found');
			}
			throw new HttpBadRequestException( $request, 'Bad id' );
		} else {
			$districts = model\District::get();
			$response->getBody()->write( json_encode( [ 'districts' => $districts ], JSON_UNESCAPED_SLASHES ) );
			return $response;
		}
	}

	public static function post( Request $request, Response $response, array $args ) : Response {
		$requester = new Requester( $request );
		if( $requester->isAdmin() ) {
			$body = $request->getParsedBody();
			if( $body ) {
				$id = model\District::new( $body['parentId'], $body['name'], $body['fullname'], $body['short'], $body['latitude'], $body['longitude'], $body['level'], $body['moderatorId'], $requester->getId() );
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
				$updated = model\District::set($body['id'], $body['name'], $body['fullname'], $body['short'], $body['latitude'], $body['longitude'], $body['level'], $body['moderatorId'], $requester->getId() );
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
				$deleted = model\District::del( $id );
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

	public static function breeders( Request $request, Response $response, array $args ) : Response {
		$id = $args[ 'id' ];
		if( is_numeric( $id ) ) {
			$breeders = model\District::getBreeders( $id );
			$response->getBody()->write(json_encode(['breeders' => $breeders], JSON_UNESCAPED_SLASHES));
			return $response;
		}
		throw new HttpBadRequestException( $request, 'Bad id' );
	}

	public static function children(Request $request, Response $response, array $args ) : Response {
		$id = $args[ 'id' ];
		if( is_numeric( $id ) ) {
			$children = model\District::getChildren( $id );
			$response->getBody()->write(json_encode(['children' => $children], JSON_UNESCAPED_SLASHES));
			return $response;
		}
		throw new HttpBadRequestException( $request, 'Bad id' );
	}

	public static function descendants( Request $request, Response $response, array $args ) : Response {
		$id = $args[ 'id' ];
		if( is_numeric( $id ) ) {
			$districts = model\District::getDescendants($id); // get all districts including root
			if( $districts ) {
				$rootDistrict = ToolBox::toTree($districts);
				if ($rootDistrict) {
					$response->getBody()->write(json_encode(['district' => & $rootDistrict], JSON_UNESCAPED_SLASHES));
					return $response;
				}
				throw new HttpInternalServerErrorException($request, 'No root district... wierd, please inform admin');
			}
			throw new HttpNotFoundException($request, 'root district not found');
		}
		throw new HttpBadRequestException( $request, 'Bad id' );
	}

    // for results edit list for section showing unopened breeds
	public static function results( Request $request, Response $response, array $args ) : Response {
		$id = ToolBox::toInt( $args[ 'id' ] );
		$query = $request->getQueryParams();
		$year = ToolBox::toInt( $query[ 'year' ] ?? null );
		$sectionId = ToolBox::toInt( $query[ 'section' ] ?? null );
		$group = $query[ 'group' ] ?? null;
		if( $id && $year && $sectionId && $group ) { // all not null and > 0 as all id's should
			if( $sectionId === 9999 ) { // aoc klasse
				$results = model\District::getAocResults( $id, $year, $group );
			} else {
				$results = model\District::getSectionResults( $id, $sectionId, $year, $group );
			}
			$response->getBody()->write(json_encode( [ 'results' => & $results ], JSON_UNESCAPED_SLASHES));
			return $response;
		}
		throw new HttpBadRequestException( $request, 'Bad arguments values' );
	}

    // for results edit list when opening breed
	public static function breedResults( Request $request, Response $response, array $args ) : Response {
		$id = ToolBox::toInt( $args[ 'id' ] );
		$breedId = ToolBox::toInt($args[ 'breed' ] ?? null );
		$query = $request->getQueryParams();
			$year = ToolBox::toInt($query[ 'year' ] ?? null );
			$sectionId = ToolBox::toInt($query[ 'section' ] ?? null );
			$group = $query[ 'group' ] ?? null;
		if( $id && $year && $sectionId && $breedId && $group ) { // all not null and > 0 or filled string
			$results = $sectionId == 5 ? // == as sectionId is text
				model\District::getBreedResult($id, $breedId, $year, $group) :
				model\District::getColorResults($id, $breedId, $year, $group);
			$response->getBody()->write(json_encode( [ 'results' => & $results ], JSON_UNESCAPED_SLASHES));
			return $response;
		}
		throw new HttpBadRequestException( $request, 'Bad arguments values' );
	}

	// returns section/subsection/breed/color tree results for generating table
	public static function report( Request $request, Response $response, array $args ) : Response {
		Logger::add( null, $request );

		$json = model\Cache::get( 'Result', $request->getUri()->getPath(), $request->getUri()->getQuery() );
        if( $json ) { // in cache
            $response->getBody()->write( $json );
            return $response;
        }
		$id = $args[ 'id' ];
		$year = $args[ 'year' ];
		if( is_numeric( $id ) && is_numeric( $year ) ) {
			$results = model\Result::getResultsDistrictYear( $id, $year );
			$report = ToolBox::toReportTree( $results );
			if( $report ) {
                $json = json_encode( [ 'report' => & $report ], JSON_UNESCAPED_SLASHES);
				$response->getBody()->write( $json );
                model\Cache::set( 'Result', $request->getUri()->getPath(), $request->getUri()->getQuery(), $json );
				return $response;
			}
			throw new HttpInternalServerErrorException($request, 'No root district... wierd, please inform admin');
		}
		throw new HttpBadRequestException( $request, 'Bad id or year' );
	}

}
