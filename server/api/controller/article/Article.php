<?php

namespace App\controller\article;

use App\controller\BaseController;
use App\query;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;

class Article extends BaseController
{

	protected function get() {
		$article = query\Article::get( $this->args[ 'id' ] );
		if( $article ) {
			return ['article' => $article];
		}
		throw new HttpNotFoundException( $this->request, 'Article not found' );

	}
	protected function post() {
		$data = $this->data;
		$id = $data[ 'id' ] ?? null;// get it or null
		if( $id ) { // 1...
			query\Article::set( $id, $data['title'], $data['html'], $this->requester->getId() );
		} else { // no id yet, so new
			$id = query\Article::new( $data['title'], $data['html'], $this->requester->getId() );
		}
		return [ 'id' => $id ];
	}
	protected function delete() {
		$id = $this->args[ 'id' ];
		if( $id ) {
			$ok = query\Article::del( $id );
			return [ 'id'=>$id, 'deleted'=>$ok ];
		}
		throw new HttpNotFoundException( $this->request );
	}
	protected function canRead() : bool {
		return true;
	}
	protected function canWrite() : bool {
		return $this->requester->isAdmin();
	}

}