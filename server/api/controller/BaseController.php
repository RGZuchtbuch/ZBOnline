<?php

namespace App\controller;


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotImplementedException;

class BaseController
{
	protected Request $request;
	protected ? Requester $requester;
	protected Response $response;

	protected array $args;
	protected array $query;
	protected ? array $data;

	function __invoke( Request $request, Response $response, array $args ) {
		$this->request = $request;
		$this->requester = Requester::extract( $request );
		$this->data = json_decode( $request->getBody(), true );
		$this->query = $request->getQueryParams();
		$this->response = $response;
		$this->args = $args;

		$method = $request->getMethod();
		// requester, authorized etc...
		$result = null;
		if( $method === "GET" && $this->canRead() ) $result = $this->get();
		else if ( $method === 'PUT' && $this->canWrite() ) $result = $this->put();
		else if ( $method === 'POST' && $this->canWrite() ) $result = $this->post();
		else if ( $method === 'DELETE' && $this->canWrite() ) $result = $this->delete();
		else throw new HttpNotImplementedException( $request );

		$json = json_encode( $result, JSON_UNESCAPED_SLASHES );
		$response->getBody()->write( $json );
		return $response;
	}

	protected function get() {
		throw new HttpNotImplementedException( $this->request );
	}
	protected function put() {
		throw new HttpNotImplementedException( $this->request );
	}
	protected function post() {
		throw new HttpNotImplementedException( $this->request );
	}
	protected function delete() {
		throw new HttpNotImplementedException( $this->request );
	}

	protected function canRead() : bool {
		return false;
	}
	protected function canWrite() : bool {
		return false;
	}
}