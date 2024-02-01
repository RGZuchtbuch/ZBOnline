<?php

namespace App\controller\breeder;

use App\controller\BaseController;
use App\query;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpNotImplementedException;

class Breeder extends BaseController
{
	protected function get() {
		$id = $this->args[ 'id' ] ?? null;
		$breeder = query\Breeder::get( $id );
		if( $breeder ) {
//			$breeder[ 'club' ] = $breeder[ 'clubId' ] ? query\District::get( $breeder[ 'clubId' ] ) : null;
			$breeder[ 'district' ] = $breeder[ 'districtId' ] ? query\District::get( $breeder[ 'districtId' ] ) : null;
			return ['breeder' => $breeder];
		}
		throw new HttpNotFoundException( $this->request, 'Breeder not found' );

	}
	protected function post() {
		$data = $this->data;
		$id = $data[ 'id' ] ?? null;
		if( $id ) {
			query\Breeder::set( $id, $data['firstname'], $data['infix'], $data['lastname'], $data['email'], $data['club'], $data['start'], $data['end'], $data['info'], $this->requester->getId() ); // note districtId and id do not change
		} else {
			$id = query\Breeder::new( $data['firstname'], $data['infix'], $data['lastname'], $data['email'], $data['districtId'], $data['club'], $data['start'], $data['end'], $data['info'], $this->requester->getId() );
		}
//		query\Cache::del( 'results' );
		return ['id' => $id];
	}
	protected function delete() {
		throw new HttpNotImplementedException( $this->request ); // yet, should be inactive if ever reported...
	}

	protected function canRead() : bool {
		$id = $this->args[ 'id'] ?? null;
		return $this->requester->isAdmin() || $this->requester->isModerator() || ( $this->requester->isSelf( $id ) );
	}
	protected function canWrite() : bool { // admin or the districts moderator
		$districtId = $this->data['districtId'] ?? null;
		return $this->requester->isAdmin() || ( $this->requester->isModerating( $districtId ) );
	}

}