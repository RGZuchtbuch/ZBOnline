<?php

namespace App\controller\breed;

use App\model;
use App\controller\BaseController;
use Slim\Exception\HttpNotFoundException;

class Colors extends BaseController
{
	protected function get() : array {
		$id = $this->args[ 'id' ];
		$colors = model\Breed::colors( $id );
		return [ 'colors'=>$colors ];
	}


	protected function canRead() : bool {
		return true;
	}
}
