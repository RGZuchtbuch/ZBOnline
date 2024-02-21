<?php

namespace App\controller;

use App\model;
use App\model\Requester;
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
				if ($district) {
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
				$id = model\District::new( $body['parentId'], $body['name'], $body['fullname'], $body['short'], $body['latitude'], $body['longitude'], $body['level'], $body['moderatorId'], $requester['id']);
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
				$updated = model\District::set($body['id'], $body['name'], $body['fullname'], $body['short'], $body['latitude'], $body['longitude'], $body['level'], $body['moderatorId'], $requester['id']);
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
			$breeders = model\District::breeders( $id );
			$response->getBody()->write(json_encode(['breeders' => $breeders], JSON_UNESCAPED_SLASHES));
			return $response;
		}
		throw new HttpBadRequestException( $request, 'Bad id' );
	}

	public static function children(Request $request, Response $response, array $args ) : Response {
		$id = $args[ 'id' ];
		if( is_numeric( $id ) ) {
			$children = model\District::children( $id );
			$response->getBody()->write(json_encode(['children' => $children], JSON_UNESCAPED_SLASHES));
			return $response;
		}
		throw new HttpBadRequestException( $request, 'Bad id' );
	}

	public static function descendants( Request $request, Response $response, array $args ) : Response {
		$id = $args[ 'id' ];
		if( is_numeric( $id ) ) {
			$districts = model\district::descendants($id); // get all districts including root
			if( $districts ) {
				$rootDistrict = ToolBox::tree($districts);
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

}
