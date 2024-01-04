<?php

namespace App\controller\color;

use App\controller\BaseController;
use App\query;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpNotImplementedException;

class Color extends BaseController
{
	protected function get() {
		$id = $this->args[ 'id' ];
		$color = query\Color::get( $id );
		if( $color ) {
			return ['color' => $color ];
		}
		throw new HttpNotFoundException( $this->request, 'Breed not found' );
	}
	protected function post() {
		$data = $this->data;
		$id = $data[ 'id' ] ?? null;// get it or null
		if( $id ) {
			query\Color::set( $id, $data['name'], $data['breedId'], $data['aoc'], null, $this->requester->getId() ); // $data['info']
		} else {
			$id = query\Color::new( $data['name'], $data['breedId'], $data['aoc'], null, $this->requester->getId() ); // $data['info'],
		}
//		query\Cache::del( 'standard' );
//		query\Cache::del( 'results' );
		return ['id' => $id ];
	}

	// delete not supported, could be if not use in results, pairs etc.
	protected function delete() {
		$id = $this->args[ 'id' ];
		$success = query\Color::del( $id );
		if( $success ) {
			//query\Cache::del( 'standard' );
			return [ 'id'=>$id, 'success'=>$success ];
		}
		throw new HttpNotFoundException( $this->request, 'Breed not found' );
//		throw new HttpNotImplementedException( $this->request );
	}
	protected function canRead() : bool {
		return true; // anyone
	}
	protected function canWrite() : bool {
		return $this->requester->isAdmin();
	}

}