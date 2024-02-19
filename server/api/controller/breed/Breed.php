<?php

namespace App\controller\breed;

use App\controller\BaseController;
use App\controller\Requester;
use App\model;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpNotImplementedException;
use Slim\Exception\HttpUnauthorizedException;

class Breed extends BaseController
{

	public function read( Request $request, Response $response, array $args ) : Response {
		$id = $args[ 'id' ];
		if( is_numeric( $id ) ) {
			$breed = model\Breed::get($id);
			if ($breed) {
				$response->getBody()->write(json_encode(['breed' => $breed], JSON_UNESCAPED_SLASHES));
				return $response;
			}
			throw new HttpNotFoundException($request, 'Breed not found');
		}
		throw new HttpBadRequestException( $request, 'Bad id' );
	}

	public function create( Request $request, Response $response, array $args ) : Response {
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

	public function update( Request $request, Response $response, array $args ) : Response {
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
                    if( ! model\Breed::colors( $id ) && ! model\Pair::allWithBreed( $id ) && ! model\Result::allWithBreed( $id ) ) { // no more color, pair of result using it
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
//		throw new HttpNotImplementedException( $request, 'not implemented yet, should only be possible if not used.');
    }

//****************************
	public function getAll( Request $request, Response $response, array $args ) : Response {
		$breeds = model\Breed::all();
		$response->getBody()->write( json_encode( [ 'breeds' => $breeds ], JSON_UNESCAPED_SLASHES ) );
		return $response;
	}

	public function getColors( Request $request, Response $response, array $args ) : Response {
		$id = $args[ 'id' ] ?? null;
		if( is_numeric( $id ) ) {
			$colors = model\Breed::colors( $id );
			if ($colors) {
				$response->getBody()->write(json_encode(['colors' => $colors], JSON_UNESCAPED_SLASHES));
				return $response;
			}
			throw new HttpNotFoundException($request, 'Breed or colors not found');
		}
		throw new HttpBadRequestException( $request, 'Bad id' );
	}


/*
	protected function get() {
		$id = $this->args[ 'id' ] ?? null;
		if( $id ) {
			$breed = model\Breed::get($id);
			if ( $breed ) {
				$breed['colors'] = model\Breed::colors($id);
				return ['breed' => $breed];
			}
		}
		throw new HttpNotFoundException( $this->request, 'Breed not found' );
	}
	protected function post() {
		$data = $this->data;
		$id = $data[ 'id' ] ?? null;// get it or null
		if( $id ) {
			model\Breed::set( $id, $data['name'], $data['sectionId'], $data['broodGroup'], $data['layEggs'], $data['layWeight'], $data['sireRing'], $data['dameRing'], $data['sireWeight'], $data['dameWeight'], null, $this->requester->getId() ); //$data['info']
		} else {
			$id = model\Breed::new( $data['name'], $data['sectionId'], $data['broodGroup'], $data['layEggs'], $data['layWeight'], $data['sireRing'], $data['dameRing'], $data['sireWeight'], $data['dameWeight'], null, $this->requester->getId() ); // $data['info']
		}
		model\Cache::del( 'standard' );
		model\Cache::del( 'results' );
		return ['id' => $id];
	}

	// delete not supported, could be if not use in colors, results etc.
	protected function delete() {
		$id = $this->args[ 'id' ];
		// only is no colors
		$colors = model\Breed::colors( $id );
		if( count( $colors ) === 0 ) { // only delete if no more colours
			$success = model\Breed::delete( $id );
			if( $success ) {
				return [ 'success' => true];
			}
			throw new HttpNotFoundException( $this->request );
		}
		throw new HttpBadRequestException( $this->request, 'Could not delete breed, its use for colors or pairs' );
	}

	protected function canRead() : bool {
		return true;
	}
	protected function canWrite() : bool {
		return $this->requester->isAdmin();
	}
*/
}