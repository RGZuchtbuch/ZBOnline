<?php

namespace App\controller\breed;

use App\controller\BaseController;
use App\query;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

class Breed extends BaseController
{
	protected function get() {
		$id = $this->args[ 'id' ] ?? null;
		if( $id ) {
			$breed = query\Breed::get($id);
			if ( $breed ) {
				$breed['colors'] = query\Breed::colors($id);
				return ['breed' => $breed];
			}
		}
		throw new HttpNotFoundException( $this->request, 'Breed not found' );
	}
	protected function post() {
		$data = $this->data;
		$id = $data[ 'id' ] ?? null;// get it or null
		if( $id ) {
			query\Breed::set( $id, $data['name'], $data['sectionId'], $data['broodGroup'], $data['layEggs'], $data['layWeight'], $data['sireRing'], $data['dameRing'], $data['sireWeight'], $data['dameWeight'], null, $this->requester->getId() ); //$data['info']
		} else {
			$id = query\Breed::new( $data['name'], $data['sectionId'], $data['broodGroup'], $data['layEggs'], $data['layWeight'], $data['sireRing'], $data['dameRing'], $data['sireWeight'], $data['dameWeight'], null, $this->requester->getId() ); // $data['info']
		}
		query\Cache::del( 'standard' );
		query\Cache::del( 'results' );
		return ['id' => $id];
	}

	// delete not supported, could be if not use in colors, results etc.
	protected function delete() {
		$id = $this->args[ 'id' ];
		// only is no colors
		$colors = query\Breed::colors( $id );
		if( count( $colors ) === 0 ) { // only delete if no more colours
			$success = query\Breed::del( $id );
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

}