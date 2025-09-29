<?php

namespace App\controller;

use App\model;
use App\model\Requester;
use DateTime;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;

class Log
{

	public static function filter(Request $request, Response $response, array $args): Response
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
//			$query = $request->getQueryParams();
//			$until = DateTime::createFromFormat( 'Y-m-d', $query['until'] ?? date('Y-m-d' ) );// provided or until today
			$until = date( 'Y-m-d' );
			$ok = model\Log::clear();
			$response->getBody()->write(json_encode(['success' => $ok, 'until' => $until ], JSON_UNESCAPED_SLASHES));
			return $response;
		}
		throw new HttpUnauthorizedException($request, 'Cannot do this');
	}
}

