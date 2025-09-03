<?php

namespace App\util;

use App\model;
use App\model\Requester;
use Slim\Psr7\Request;

class Logger
{
	public static function log( ? Requester $requester, ? Request $request, ? string $message ) : bool {
		$requesterId = $requester?->getId();
		$method      = $request?->getMethod();
		$path        = $request?->getUri()->getPath();
		$query       = $request?->getUri()->getQuery();
		$body        = $request?->getBody();

		if( $path !== '/api/user/token') { // not for login, would expose password
			$id = model\Log::log(
				$method,
				$path,
				$query,
				$body,
				$requesterId,
				$message
			);
			return (bool) $id; // if added successfully
		}
		return false;
	}
}