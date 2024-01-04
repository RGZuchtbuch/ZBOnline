<?php

namespace App\controller;

use DateTimeImmutable;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Slim\Exception\HttpNotFoundException;

class Token
{
	public static function encode( array $data ) : string {
		$issuer = 'RG Zuchtbuch Online';
		$issued = new DateTimeImmutable();
		$expires = $issued->modify('+'.TOKEN_EXPIRE.' minutes'); // till so many minutes after now

		$payload = [
			'iss'   => $issuer,
			'iat'   => $issued->getTimestamp(),
			'nbf'   => $issued->getTimestamp(),
			'exp'   => $expires->getTimestamp(),
			'user'  => (array) $data
		];

		return JWT::encode( $payload, TOKEN_SECRET, TOKEN_ALGORITHM );
	}
	public static function decode( string $token ) : array {
		$payload = (array)JWT::decode($token, new Key(TOKEN_SECRET, TOKEN_ALGORITHM));
		$payload['user'] = (array)$payload['user']; // convert back to 'normal' array
		return $payload;
		// may throw ExpiredException $e
	}
}