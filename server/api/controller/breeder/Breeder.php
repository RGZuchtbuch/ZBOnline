<?php

namespace App\controller\breeder;

use App\controller\BaseController;
use App\controller\Requester;
use App\model;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpNotImplementedException;
use Slim\Exception\HttpUnauthorizedException;

class Breeder extends BaseController
{

	public function get( Request $request, Response $response, array $args ) : Response {
		$id = $args[ 'id' ] ?? null;
		if( is_numeric( $id ) ) {
			$requester = new Requester( $request );
			$breeder = model\Breeder::get($id);
			if( $breeder ) {
				$districtId = $breeder[ 'districtId' ] ?? null;
				if( $requester->isAdmin() || $requester->isModerating( $districtId ) || $requester->hasId( $id ) ) {
					$response->getBody()->write(json_encode(['breeder' => $breeder], JSON_UNESCAPED_SLASHES));
					return $response;
				}
				throw new HttpUnauthorizedException( $request, 'Cannot do this' );
			}
			throw new HttpNotFoundException($request, 'Breeder not found');
		}
		throw new HttpBadRequestException( $request, 'Bad id provided' );
	}

	public function new( Request $request, Response $response, array $args ) : Response {
		$requester = new Requester( $request );
		$body = $request->getParsedBody();
		if( $body ) {
			$districtId = $body['districtId'] ?? null;
			if( $requester->isAdmin() || $requester->isModerating( $districtId ) ) {
				$id = model\Breeder::new( $body['firstname'], $body['infix'], $body['lastname'], $body['email'], $body['districtId'], $body['club'], $body['start'], $body['end'], $body['info'], $requester->getId() );
				if( $id ) {
					$response->getBody()->write(json_encode(['id' => $id], JSON_UNESCAPED_SLASHES));
					return $response;
				}
				throw new HttpBadRequestException( $request, 'Bad body' );
			}
			throw new HttpUnauthorizedException( $request, 'Cannot do this');
		}
		throw new HttpBadRequestException( $request, 'Bad body' );
	}

	public function set( Request $request, Response $response, array $args ) : Response {
		$requester = new Requester( $request );
		$id = $args[ 'id' ] ?? null;
		$body = $request->getParsedBody();
		if( is_numeric( $id ) && $body ) {
			$districtId = $body['districtId'] ?? null;
			if( $requester->isAdmin() || $requester->isModerating( $districtId ) ) {
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

	public function del( Request $request, Response $response, array $args ) : Response {
		throw new HttpNotImplementedException( $request );
	}

// ****************************************
	public function getAll( Request $request, Response $response, array $args ) : Response {
		$breeders = model\Breeder::getAll();
		$response->getBody()->write( json_encode( [ 'breeders' => $breeders ], JSON_UNESCAPED_SLASHES ) );
		return $response;
	}

}