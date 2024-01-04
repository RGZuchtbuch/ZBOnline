<?php

namespace App\controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use App\controller\user\GetToken;

class Requester
{

	static function extract( Request $request ) : ? Requester {
		$authorization = $request->getHeaderLine( 'Authorization' );
		if( ! empty( $authorization ) ) {
			$header = trim( $authorization ); // get rid of leading or trailing spaces
			if (preg_match('/Bearer\s(\S+)/', $header, $matches )) { // get token part of header
				$token = $matches[1];
				if( $token ) {
					$payload = GetToken::decode( $token );
					if( $payload ) {
						$user = $payload[ 'user' ];
						return new Requester( $user[ 'id' ], $user[ 'admin' ], $user[ 'moderator' ] );
					}
				}
			}
		}
		return new Requester(0, false, [] ); // guest
	}


	// instance
	private int $id;
	private bool $admin;
	private array $moderates;

	function __construct( int $id, bool $admin, array $moderates ) {
		$this->id = $id;
		$this->admin = $admin;
		$this->moderates = $moderates;
	}

	public function getId() {
		return $this->id;
	}
	public function isAdmin() : bool {
		return $this->admin;
	}
	public function isMember() : bool {
		return $this->id && $this->id > 0;
	}
	public function isModerator() : bool {
		return count( $this->moderates ) > 0; // moderates any
	}
	public function isModerating( int $id ) : bool {
		return in_array( $id, $this->moderates );
	}
	public function isSelf( int $id ) : bool {
		return $this->id === $id;
	}

}