<?php

namespace App\controller;

use App\model;
use App\model\Requester;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpNotImplementedException;
use Slim\Exception\HttpUnauthorizedException;

class Breeder
{

	public static function get( Request $request, Response $response, array $args ) : Response {
		$id = $args[ 'id' ] ?? null;
		if( $id ) { // breeder
			if( is_numeric( $id ) ) {
				$requester = new Requester( $request );
				$breeder = model\Breeder::get($id);
				if( $breeder ) {
					$districtId = $breeder[ 'districtId' ] ?? null;
					if( $requester->isAdmin() || $requester->isModerating( $districtId ) || $requester->hasId( $id ) ) { //admin of the moderator or self
						$response->getBody()->write(json_encode(['breeder' => $breeder], JSON_UNESCAPED_SLASHES));
						return $response;
					}
					throw new HttpUnauthorizedException( $request, 'Cannot do this' );
				}
				throw new HttpNotFoundException($request, 'Not found');
			}
			throw new HttpBadRequestException( $request, 'Bad id provided' );
		} else { // list
			$requester = new Requester( $request );
			if( $requester->isAdmin() ) { // only admin
				$breeders = model\Breeder::getAll();
				$response->getBody()->write(json_encode(['breeders' => $breeders], JSON_UNESCAPED_SLASHES));
				return $response;
			} else {
				throw new HttpUnauthorizedException($request, 'Not Admin');
			}
		}

	}

	public static function post( Request $request, Response $response, array $args ) : Response {
		$requester = new Requester( $request );
		$body = $request->getParsedBody();
		if( $body ) {
			$district = model\District::get( $body[ 'districtId' ] );
			if( $requester && $district ) {
				if ($requester->isAdmin() || $requester->isModerating( $district['id'] )) { //admin or the moderator
					$id = model\Breeder::new($body['firstname'], $body['infix'], $body['lastname'], $body['email'], $body['districtId'], $body['club'], $body['start'], $body['end'], $body['info'], $requester->getId());
					if ($id) {
						$response->getBody()->write(json_encode(['id' => $id], JSON_UNESCAPED_SLASHES));
						return $response;
					}
					throw new HttpInternalServerErrorException( $request, 'Could not create breeder' );
				}
				throw new HttpUnauthorizedException( $request, 'Cannot do this' );
			}
			throw new HttpBadRequestException( $request, 'District does not exist' );
		}
		throw new HttpBadRequestException( $request, 'Missing body' );
	}

	public static function put( Request $request, Response $response, array $args ) : Response {
		$requester = new Requester( $request );
		$id = $args[ 'id' ] ?? null;
		$body = $request->getParsedBody();
		if( is_numeric( $id ) && $body ) {
			$districtId = $body['districtId'] ?? null;
			if( $requester->isAdmin() || $requester->isModerating( $districtId ) ) { //admin of the moderator
				$success = model\Breeder::set($id, $body['firstname'], $body['infix'], $body['lastname'], $body['email'], $body['club'], $body['start'], $body['end'], $body['info'], $requester->getId());
				if ($success) {
					$response->getBody()->write(json_encode(['id' => $id], JSON_UNESCAPED_SLASHES));
					return $response;
				}
				throw new HttpNotFoundException($request, 'Cannot update');
			}
			throw new HttpUnauthorizedException($request, 'not Admin');
		}
		throw new HttpBadRequestException($request, 'Bad id or body');
	}

	public static function delete( Request $request, Response $response, array $args ) : Response {
		throw new HttpNotImplementedException( $request );
	}

	/** other getters **/


	public static function pairs( Request $request, Response $response, array $args ) : Response {
		$id = $args[ 'id' ] ?? null;
		if( is_numeric( $id ) ) {
			$requester = new Requester( $request );
			$breeder = model\Breeder::get($id);
			if( $breeder ) {
				$districtId = $breeder[ 'districtId' ] ?? null;
				if( $requester->isAdmin() || $requester->isModerating( $districtId ) || $requester->hasId( $id ) ) { //admin of the moderator or self
					$pairs = model\Breeder::getPairs( $id );
					$response->getBody()->write(json_encode([ 'breeder'=>$breeder, 'pairs' => $pairs], JSON_UNESCAPED_SLASHES));
					return $response;
				}
				throw new HttpUnauthorizedException( $request, 'Cannot do this' );
			}
			throw new HttpNotFoundException($request, 'Breeder not found');
		}
		throw new HttpBadRequestException( $request, 'Bad id provided' );
	}

	public static function yearPairs( Request $request, Response $response, array $args ) : Response {
		$id = $args[ 'id' ] ?? null;
		$year = $args[ 'year' ] ?? null;
		if( is_numeric( $id ) && is_numeric( $year ) ) {
			$requester = new Requester( $request );
			$breeder = model\Breeder::get($id);
			if( $breeder ) {
				$districtId = $breeder[ 'districtId' ] ?? null;
				if( $requester->isAdmin() || $requester->isModerating( $districtId ) || $requester->hasId( $id ) ) { //admin of the moderator or self
					$pairs = model\Breeder::getPairsInYear( $id, $year );
					$response->getBody()->write(json_encode([ 'pairs' => $pairs ], JSON_UNESCAPED_SLASHES));
					return $response;
				}
				throw new HttpUnauthorizedException( $request, 'Cannot do this' );
			}
			throw new HttpNotFoundException($request, 'Breeder not found');
		}
		throw new HttpBadRequestException( $request, 'Bad id provided' );
	}
}