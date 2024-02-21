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

class Color
{


	public static function get( Request $request, Response $response, array $args ) : Response {
		$id = $args[ 'id' ] ?? null;
		if( $id ) {
			if (is_numeric($id)) {
				$color = model\Color::get($id);
				if ($color) {
					$response->getBody()->write(json_encode(['color' => $color], JSON_UNESCAPED_SLASHES));
					return $response;
				}
				throw new HttpNotFoundException($request, 'Color not found');
			}
			throw new HttpBadRequestException($request, 'Bad id');
		} else {
			$colors = model\Color::get();
			$response->getBody()->write( json_encode( [ 'colors' => $colors ], JSON_UNESCAPED_SLASHES ) );
			return $response;
		}
	}

	public static function create( Request $request, Response $response, array $args ) : Response {
		$requester = new Requester( $request );
		if( $requester->isAdmin() ) { // only admin can do this
			$body = $request->getParsedBody();
			if( $body ) {
				$breed = model\Breed::get( $body[ 'breedId' ] );
				if( $breed ) {
					$id = model\Color::new($body['name'], $body['breedId'], $body['aoc'], null, $requester->getId() );
					if ($id) {
						$response->getBody()->write(json_encode(['id' => $id], JSON_UNESCAPED_SLASHES));
						return $response;
					}
					throw new HttpInternalServerErrorException( $request, 'Error creating ne color' );
				}
				throw new HttpBadRequestException( $request, 'Breed for color does not exist' );
			}
			throw new HttpBadRequestException( $request, 'Missing body' );
		}
		throw new HttpUnauthorizedException( $request, 'Cannot do this');
	}

	public static function update( Request $request, Response $response, array $args ) : Response {
		$requester = new Requester( $request );
		if( $requester->isAdmin() ) {
			$id = $args[ 'id' ] ?? null;
			if( $id && is_numeric( $id ) ) {
				$body = $request->getParsedBody();
				if ($body) {
					$updated = model\Color::set($id, $body['name'], $body['aoc'], null, $requester->getId());
					if ($updated) {
						$response->getBody()->write(json_encode(['id' => $id, 'updated'=>$updated], JSON_UNESCAPED_SLASHES));
						return $response;
					}
					throw new HttpNotFoundException($request, 'Cannot update');
				}
				throw new HttpBadRequestException($request, 'Missing body');

			}
			throw new HttpBadRequestException($request, 'Bad id');
		}
		throw new HttpUnauthorizedException( $request, 'Cannot do this');
	}

	public static function delete( Request $request, Response $response, array $args ) : Response {
        $requester = new Requester( $request );
        if( $requester->isAdmin() ) {
            $id = $args[ 'id' ] ?? null;
            if( $id && is_numeric( $id ) ) {
                $color = model\Color::get( $id );
                if ($color) {
                    $pairs = model\Pair::allWithColor( $id );
                    $results = model\Result::allWithColor( $id );
                    if( ! $pairs && ! $results ) { // not used in either
                        $success = model\Color::del( $id );
                        $response->getBody()->write(json_encode(['success' => $success, 'id'=>$id], JSON_UNESCAPED_SLASHES));
                       return $response;
                   }
                    throw new HttpBadRequestException($request, 'Color in use');
                }
                throw new HttpBadRequestException($request, 'Color not found');
            }
            throw new HttpBadRequestException($request, 'Bad id');
        }
        throw new HttpUnauthorizedException( $request, 'Cannot do this');
//		throw new HttpNotImplementedException( $request, 'not implemented yet, should only be possible if not used.');
	}

// ****************************************


}