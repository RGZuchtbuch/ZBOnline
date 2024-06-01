<?php

namespace App\util;

use App\model;
use App\model\Requester;
use Slim\Psr7\Request;

class Logger
{
	public static function add( ? Requester $requester, Request $request ) : bool {
		$requesterId = $requester ? $requester->getId() : null;
		$method = $request->getMethod();
		$path = $request->getUri()->getPath();
		$query = $request->getUri()->getQuery();
		$body = $request->getBody();

		if( $path !== '/api/user/token') { // not for login, would expose password
			$id = model\Log::new(
				$method,
				$path,
				$query,
				$body,
				$requesterId
			);
			return (bool) $id; // if added successfully
		}
		return false;
	}
}