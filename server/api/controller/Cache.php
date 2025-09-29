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

class Cache
{
	public static function clear(Request $request, Response $response, array $args): Response
	{
		$requester = new Requester($request);
		if ($requester->isAdmin()) {
			$ok = model\Cache::clear();
			$response->getBody()->write(json_encode(['success' => $ok ], JSON_UNESCAPED_SLASHES));
			return $response;
		}
		throw new HttpUnauthorizedException($request, 'Cannot do this');
	}
}

