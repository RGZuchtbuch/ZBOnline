<?php

namespace App\controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\controller\BaseController;
use App\model;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;


function getArg( array $args, string $arg ) : mixed {
	return $args[ $arg ] ?? null;
}
function write( Response $response, array $body ) : Response {
	$response->getBody()->write( json_encode( $body, JSON_UNESCAPED_SLASHES ) );
	return $response;
}

function getData( Request $request ) : array {
	return $request->getParsedBody();
//		return json_decode( $request->getBody(), true );
}


class Breed
{

	static function list(Request $request, Response $response, array $args ) : Response {
		$breeds = model\Breed::all();
		write( $response, [ 'breeds'=>$breeds ] );
		return $response;
	}

	static function get(Request $request, Response $response, array $args ) : Response {
		$id = getArg( $args, 'id' );
		if( $id ) {
			$breed = model\Breed::get($id);
			if ( $breed ) {
				$breed['colors'] = model\Breed::colors($id);

				write( $response, [ 'breed'=>$breed ] );
//				$response->getBody()->write( json_encode( [ 'breed'=>$breed ], JSON_UNESCAPED_SLASHES ) );
				return $response;
			}
			throw new HttpNotFoundException( $request, 'Breed not found' );
		}
		throw new HttpBadRequestException( $request, 'Breed not found' );
	}

	static public function getColors( Request $request, Response $response, array $args ) : Response {
		$id = getArg( $args, 'id' );
		if( $id ) {
			$colors = model\Breed::colors($id);

			write( $response, [ 'colors'=>$colors ] );
			return $response;
		}
		throw new HttpNotFoundException( $request, 'Breed not found' );
	}



	protected function canRead() : bool {
		return true;
	}
	protected function canWrite() : bool {
		return $this->requester->isAdmin();
	}




}