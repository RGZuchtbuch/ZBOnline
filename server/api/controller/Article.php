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

class Article
{

	public static function get( Request $request, Response $response, array $args ) : Response {
		$id = $args[ 'id' ] ?? null;
		if( $id ) { // specific article
			if( is_numeric( $id ) ) {
				$article = model\Article::get( $id );
				if ($article) {
					$response->getBody()->write(json_encode(['article' => $article], JSON_UNESCAPED_SLASHES));
					return $response;
				}
				throw new HttpNotFoundException($request, 'Article not found');
			}
			throw new HttpBadRequestException( $request, 'Bad id' );
		} else { // list
			$articles = model\Article::get();
			$response->getBody()->write( json_encode( [ 'articles' => $articles ], JSON_UNESCAPED_SLASHES ) );
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

}