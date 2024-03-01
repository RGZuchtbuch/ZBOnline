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
						$brood['chicks'] = model\Animal::getForBrood( $brood['id'] );
					}
					$pair['show'] = model\Show::getForPair( $pair['id'] );
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
		$id = $args['id'] ?? null;
		$body = $request->getParsedBody();
		if ($body) {
			$requester = new Requester($request);
			if ($requester && ($requester->isAdmin() || $requester->isModerating($body['districtId']) || $requester->hasId($body['breederId']))) {
				Query::begin();
				$id = Pair::postPair( $id, $body, $requester );
				if ($id && Pair::postParents( $id, $body[ 'parents' ], $requester ) && Pair::postLay( $id, $body['lay'], $requester ) && Pair::postBroods( $id, $body['broods'], $requester ) && Pair::postShow($id, $body['show'], $requester )) {
					Query::commit();
					$response->getBody()->write(json_encode(['id' => $id], JSON_UNESCAPED_SLASHES));
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
			return model\Pair::new($body['breederId'], $body['districtId'], $body['year'], $body['group'], $body['sectionId'], $body['breedId'], $body['colorId'], $body['name'], $body['paired'], $body['notes'], $requester['id']);
		} else {
			$success = model\Pair::set($body['id'], $body['breederId'], $body['districtId'], $body['year'], $body['group'], $body['sectionId'], $body['breedId'], $body['colorId'], $body['name'], $body['paired'], $body['notes'], $requester['id']);
			if( $success ) {
				return $id;
			}
			return false;
		}
	}

	public static function postParents( int $pairId, array $parents, Requester $requester ) : bool
	{
		$success = true;
		model\Pair::delParents( $pairId ); // remove existing parent links to replace by new once
		foreach ($parents as $parent) {
			$animalId = $parent['id'] ?? null; // animal exists
			if ($animalId == null) {
				$animalId = model\Animal::new( $parent['sex'], $parent['ring'], $parent['pairId'], null, $requester[ 'id' ] );
				$success &= $animalId && model\Pair::newParent($pairId, $animalId, $parent['score'], $requester['id']);
			} else {
				$success &= model\Pair::newParent( $pairId, $animalId, $parent[ 'score' ], $requester[ 'id' ] );
			}
		}
		return $success;
	}

	public static function postLay( int $pairId, array $lay, Requester $requester ) : bool {
		model\Pair::delLay( $pairId ); //remove evt existing
		return model\Pair::newLay( $pairId, $lay['start'], $lay['end'], $lay['eggs'], $lay['dames'], $lay['weight'], $lay['modifierId'] );
	}

	public static function postBroods( int $pairId, array $broods, Requester $requester ) : bool {
		$success = true;
		model\Pair::delBroods( $pairId ); // remove existing parent links to replace by new once
		foreach ($broods as $brood) {
			$success &= model\Pair::newBrood( $pairId, $brood['start'], $brood['eggs'], $brood['fertile'], $brood['hatched'], $requester['id']);
		}
		return $success;
	}
	public static function postShow( int $pairId, array $show, Requester $requester ) : bool {
		model\Pair::delShow( $pairId ); //remove evt existing
		return model\Pair::newShow( $pairId, $show['89'], $show['90'], $show['91'], $show['92'], $show['93'], $show['94'], $show['95'], $show['96'], $show['97'], $show['modifierId'] );
	}

}