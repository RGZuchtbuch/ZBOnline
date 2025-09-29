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

class Color
{


	public static function get( Request $request, Response $response, array $args ) : Response {
		$id = $args[ 'id' ] ?? null;
		if( $id ) {
			if (is_numeric($id)) {
				$color = model\std\Color::get($id);
				if ($color) {
					$response->getBody()->write(json_encode(['color' => $color], JSON_UNESCAPED_SLASHES));
					return $response;
				}
				throw new HttpNotFoundException($request, 'Color not found');
			}
		}
		throw new HttpBadRequestException($request, 'Bad or missing id');
	}

	public static function post( Request $request, Response $response, array $args ) : Response {
		$requester = new Requester( $request );
		if( $requester->isAdmin() ) { // only admin can do this
			$body = $request->getParsedBody();
			if( $body ) {
				$breed = model\std\Breed::get( $body[ 'breedId' ] );
				if( $breed ) {
					$id = model\std\Color::new($body['name'], $body['breedId'], null, $requester->getId() );
					if ($id) {
						model\Cache::delete('standard');
						model\Cache::delete('result');
						model\Cache::delete('report' ); // clear cache as results changed
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

	public static function put( Request $request, Response $response, array $args ) : Response {
		$requester = new Requester( $request );
		if( $requester->isAdmin() ) {
			$id = $args[ 'id' ] ?? null;
			if( $id && is_numeric( $id ) ) {
				$body = $request->getParsedBody();
				if ($body) {
					$updated = model\std\Color::set($id, $body['name'], null, $requester->getId());
					if ($updated) {
						model\Cache::delete('standard');
						model\Cache::delete('result');
						model\Cache::delete('report' ); // clear cache as results changed
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
                $color = model\std\Color::get( $id );
                if ($color) {
                    $pairs = model\Pair::readForColor( $id );
                    $results = model\Result::getAllWithColor( $id );
                    if( ! $pairs && ! $results ) { // not used in either
						model\Cache::delete('standard');
						model\Cache::delete('result');
						model\Cache::delete('report' ); // clear cache as results changed
                        $success = model\std\Color::del( $id );
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

// **************************************** new

	public static function filter( Request $request, Response $response, array $args ) : Response {
		$query = $request->getQueryParams();
		$breedId = $query['breed'] ?? null;
		if( is_numeric( $breedId ) ) {
			$colors = model\std\Color::breed( $breedId );
			$response->getBody()->write(json_encode(['colors' => $colors], JSON_UNESCAPED_SLASHES));
			return $response;
		}
		throw new HttpBadRequestException( $request, 'Bad id' );
	}

}