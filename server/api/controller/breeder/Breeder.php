<?php

namespace App\controller\breeder;

use App\controller\BaseController;
use App\query;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpNotImplementedException;

class Breeder extends BaseController
{
	protected function get() {
		$id = $this->args[ 'id' ];
		$breeder = query\Breeder::get( $id );
		if( $breeder ) {
			return ['breeder' => $breeder];
		}
		throw new HttpNotFoundException( $this->request, 'Breeder not found' );

	}
	protected function post() {
		$data = $this->data;
		$id = $data[ 'id' ] ?? null;
		if( $id ) {
			query\Breeder::set( $id, $data['firstname'], $data['infix'], $data['lastname'], $data['email'], $data['club']['id'], $data['start'], $data['end'], $data['info'], $this->requester->getId() ); // note districtId and id do not change
		} else {
			$id = query\Breeder::new( $data['firstname'], $data['infix'], $data['lastname'], $data['email'], $data['district']['id'], $data['club']['id'], $data['start'], $data['end'], $data['info'], $this->requester->getId() );
		}
//		query\Cache::del( 'results' );
		return ['id' => $id];
	}
	protected function delete() {
		throw new HttpNotImplementedException( $this->request );
	}

	protected function canRead() : bool {
		return $this->requester && $this->data && ( $this->requester->isAdmin() || $this->requester->isModerator() || $this->requester->isSelf( $this->data[ 'id'] ) );
	}
	protected function canWrite() : bool { // admin or the districts moderator
		return $this->requester && $this->data && ( $this->requester->isAdmin() || $this->requester->isModerating( $this->data['districtId'] ) );
	}

}