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

class Breed
{

	public static function get( Request $request, Response $response, array $args ) : Response {
		$id = $args[ 'id' ] ?? null;
		if( $id ) { // specific breed
			if( is_numeric( $id ) ) {
				$breed = model\Breed::get($id);
				if ($breed) {
					$response->getBody()->write(json_encode(['breed' => $breed], JSON_UNESCAPED_SLASHES));
					return $response;
				}
				throw new HttpNotFoundException($request, 'Breed not found');
			}
			throw new HttpBadRequestException( $request, 'Bad id' );
		} else { // list
			$breeds = model\Breed::get();
			$response->getBody()->write( json_encode( [ 'breeds' => $breeds ], JSON_UNESCAPED_SLASHES ) );
			return $response;
		}
	}

	public static function post( Request $request, Response $response, array $args ) : Response {
		$requester = new Requester( $request );
		if( $requester->isAdmin() ) {
			$body = $request->getParsedBody();
			if( $body ) {
				$section = model\Section::get( $body[ 'sectionId' ] );
				if( $section ) {
					$id = model\Breed::new($body['name'], $body['sectionId'], $body['broodGroup'], $body['layEggs'], $body['layWeight'], $body['sireRing'], $body['dameRing'], $body['sireWeight'], $body['dameWeight'], null, $requester->getId()); // $data['info']
					if ($id) {
						model\Cache::del('standard');
						model\Cache::del('results');

						$response->getBody()->write(json_encode(['id' => $id], JSON_UNESCAPED_SLASHES));
						return $response;
					}
					throw new HttpInternalServerErrorException($request, 'Error creating new breed');
				}
				throw  new HttpBadRequestException( $request, 'Section does not exist' );
			}
			throw  new HttpBadRequestException( $request, 'Missing body' );
		}
		throw new HttpUnauthorizedException( $request, 'Cannot do this');
	}

	public static function put( Request $request, Response $response, array $args ) : Response {
		$requester = new Requester( $request );
		if( $requester->isAdmin() ) {
			$id = $args[ 'id' ] ?? null;
			$body = $request->getParsedBody();
			if( is_numeric( $id ) && $body ) {
				$success = model\Breed::set( $id, $body['name'], $body['sectionId'], $body['broodGroup'], $body['layEggs'], $body['layWeight'], $body['sireRing'], $body['dameRing'], $body['sireWeight'], $body['dameWeight'], null, $requester->getId() ); //$data['info']
				if( $success ) {
					model\Cache::del('standard');
					model\Cache::del('results');
					$response->getBody()->write(json_encode(['id' => $id], JSON_UNESCAPED_SLASHES));
					return $response;
				}
				throw new HttpNotFoundException( $request, 'Cannot update');
			}
			throw new HttpBadRequestException( $request, 'Bad id or body');
		}
		throw new HttpUnauthorizedException( $request, 'Cannot do this');
	}

//	public function del( Request $request, Response $response, array $args ) : Response {
//		throw new HttpNotImplementedException( $request, 'Oops' );
//	}

    public static function delete( Request $request, Response $response, array $args ) : Response {
        $requester = new Requester( $request );
        if( $requester->isAdmin() ) {
            $id = $args[ 'id' ] ?? null;
            if( $id && is_numeric( $id ) ) {
                $breed = model\Breed::get( $id );
                if ($breed) {
                    if( ! model\Breed::getColors( $id ) && ! model\Pair::allWithBreed( $id ) && ! model\Result::getAllWithBreed( $id ) ) { // no more color, pair of result using it
                        $success = model\Breed::delete( $id );
                        $response->getBody()->write(json_encode(['success' => $success, 'id'=>$id ], JSON_UNESCAPED_SLASHES));
                        return $response;
                    }
                    throw new HttpBadRequestException($request, 'Breed in use for Color, Pair or Result');
                }
                throw new HttpBadRequestException($request, 'Breed not found');
            }
            throw new HttpBadRequestException($request, 'Bad id');
        }
        throw new HttpUnauthorizedException( $request, 'Cannot do this');
    }

	/** other getters **/


	public static function colors( Request $request, Response $response, array $args ) : Response {
		$id = $args[ 'id' ] ?? null;
		if( is_numeric( $id ) ) {
			$colors = model\Breed::getColors( $id );
			if ($colors) {
				$response->getBody()->write(json_encode(['colors' => $colors], JSON_UNESCAPED_SLASHES));
				return $response;
			}
			throw new HttpNotFoundException($request, 'Breed or colors not found');
		}
		throw new HttpBadRequestException( $request, 'Bad id' );
	}

}