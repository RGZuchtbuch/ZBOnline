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

class Section
{

	public static function get( Request $request, Response $response, array $args ) : Response {
		$id = $args[ 'id' ] ?? null;
		if( $id ) { // section
			if( is_numeric( $id ) ) {
				$section = model\Section::get( $id );
				if ($section) {
					$response->getBody()->write(json_encode(['section' => $section], JSON_UNESCAPED_SLASHES));
					return $response;
				}
				throw new HttpNotFoundException($request, 'not found');
			}
			throw new HttpBadRequestException( $request, 'Bad id' );
		} else {
			$sections = model\Section::get();
			$response->getBody()->write( json_encode( [ 'sections' => $sections ], JSON_UNESCAPED_SLASHES ) );
			return $response;
		}
	}

	public static function post( Request $request, Response $response, array $args ) : Response {
		$requester = new Requester( $request );
		if( $requester->isAdmin() ) {
			$body = $request->getParsedBody();
			if( $body ) {
				$id = model\Article::new( $body['title'], $body['html'], $requester->getId() );
				if( $id ) {
					model\Cache::del('standard');
					model\Cache::del('result');
					$response->getBody()->write(json_encode(['id' => $id], JSON_UNESCAPED_SLASHES));
					return $response;
				}
				throw new HttpInternalServerErrorException( $request, 'Oops, error creating new article' );
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
				$updated = model\Article::set( $id, $body['title'], $body['html'], $requester->getId() );
				if( $updated ) {
					model\Cache::del('standard');
					model\Cache::del('result');
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
				$deleted = model\Article::del( $id );
				if( $deleted ) {
					model\Cache::del('standard');
					model\Cache::del('result');
					$response->getBody()->write(json_encode([ 'id'=>$id, 'deleted'=>true ], JSON_UNESCAPED_SLASHES));
					return $response;
				}
				throw new HttpNotFoundException( $request, 'Cannot delete');
			}
			throw new HttpBadRequestException( $request, 'Cannot delete');
		}
		throw new HttpUnauthorizedException( $request, 'not Admin');
	}

	/** other getters **/

	public static function breeds( Request $request, Response $response, array $args ) : Response {
		$id = $args[ 'id' ] ?? null;
		if( is_numeric( $id ) ) {
			$breeds = model\Section::getBreeds( $id );
			$response->getBody()->write(json_encode(['breeds' => $breeds], JSON_UNESCAPED_SLASHES));
			return $response;
		}
		throw new HttpBadRequestException( $request, 'Bad id' );
	}

	public static function children( Request $request, Response $response, array $args ) : Response {
		$id = $args[ 'id' ] ?? null;
		if( is_numeric( $id ) ) {
			$children = model\Section::getChildren( $id );
			$response->getBody()->write(json_encode(['sections' => $children], JSON_UNESCAPED_SLASHES));
			return $response;
		}
		throw new HttpBadRequestException( $request, 'Bad id' );
	}

	public static function descendants( Request $request, Response $response, array $args ) : Response {
		$id = $args[ 'id' ] ?? null;
		if( is_numeric( $id ) ) {
			$descendants = model\Section::getDescendants( $id );
			$root = ToolBox::toTree( $descendants );
			$response->getBody()->write(json_encode(['section' => $root], JSON_UNESCAPED_SLASHES));
			return $response;
		}
		throw new HttpBadRequestException( $request, 'Bad id' );
	}
}