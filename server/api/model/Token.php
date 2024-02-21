<?php

namespace App\model;

use DateTimeImmutable;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ServerRequestInterface as Request;

class Token
{
	private ? Requester $requester = null;
	public function __construct( Request $request ) {
		$bearer = Token::getBearer( $request );
		$payload = Token::decode( $bearer );
		$this->requester = $payload ? new Requester($payload['user']) : null;
	}

	public function getRequester() : ? array {
		return $this->payload;
	}

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

	private static function getBearer(Request $request ) : ? string {
	$authorization = $request->getHeaderLine( 'Authorization' );
	if( $authorization && !empty( $authorization ) ) {
		$header = trim($authorization);
		if (preg_match('/Bearer\s(\S+)/', $header, $matches )) { // get token part of header
			return $matches[1];
		}
	}
	return null;
}
}