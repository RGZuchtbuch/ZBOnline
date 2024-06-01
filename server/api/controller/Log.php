<?php

namespace App\controller;

use App\model;
use App\model\Requester;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;

class Log
{

	public static function next(Request $request, Response $response, array $args): Response
	{
		$requester = new Requester($request);
		if ($requester->isAdmin()) {
			$query = $request->getQueryParams();
			$from = $query['from'] ?? null;
			$count = $query['count'] ?? null;
			if ($from >= 0 && $count > 0) {
				$logs = model\Log::next($from, $count);
				$response->getBody()->write(json_encode(['logs' => $logs], JSON_UNESCAPED_SLASHES));
				return $response;
			}
			throw new HttpBadRequestException($request, 'Bad query params');
		}
		throw new HttpUnauthorizedException($request, 'Cannot do this');
	}
	public static function clear(Request $request, Response $response, array $args): Response
	{
		$requester = new Requester($request);
		if ($requester->isAdmin()) {
			$query = $request->getQueryParams();
			$untilDate = $query['until'] ?? $date = date('Y-m-d');// provided or until today
			$logs = model\Log::clear( $untilDate );
			$response->getBody()->write(json_encode(['success' => true ], JSON_UNESCAPED_SLASHES));
			return $response;
		}
		throw new HttpUnauthorizedException($request, 'Cannot do this');
	}
}

