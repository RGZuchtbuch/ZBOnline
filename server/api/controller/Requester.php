<?php

namespace App\controller;

use Psr\Http\Message\ServerRequestInterface as Request;

class Requester
{
	private ? int $id = null; // user id
	private bool $admin = false; // is admin
	private array $moderates = []; // array of districts moderated by user

	function __construct( Request $request ) {
		$authorization = $request->getHeaderLine( 'Authorization' );
		if( ! empty( $authorization ) ) {
			$header = trim( $authorization ); // get rid of leading or trailing spaces
			if (preg_match('/Bearer\s(\S+)/', $header, $matches )) { // get token part of header
				$token = $matches[1];
				if( $token ) {
					$payload = Token::decode( $token );
					if( $payload ) {
						$user = $payload[ 'user' ];
						$this->id = $user['id'];
						$this->admin = $user[ 'admin' ];
						$this->moderates = $user[ 'moderator' ];
					}
				}
			}
		}
	}

	public function getId() {
		return $this->id;
	}
	public function isGuest() : bool {
		return $this->id === null;
	}
	public function isUser() : bool {
		return $this->id !== null;
	}
	public function isAdmin() : bool {
		return $this->admin;
	}
	public function isModerator() : bool {
		return count( $this->moderates ) > 0; // moderates any
	}
	public function isModerating( int $id ) : bool {
		return in_array( $id, $this->moderates );
	}
	public function hasId( int $id ) : bool {
		return $this->id === $id;
	}

}