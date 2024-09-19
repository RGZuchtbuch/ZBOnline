<?php

namespace App\controller;

use App\model;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;

class User
{
	// get, post, put and delete through Breeder

	/** other getters **/

	public static function login( Request $request, Response $response, array $args ) : Response { // get token
		$body = $request->getParsedBody();
		if( $body ) {
			$email = $body[ 'email' ] ?? null;
			$password = $body[ 'password' ] ?? null;
			if( $email && $password ) {
				$id = model\User::authenticate( $email, $password );
				if( $id ) {
					$user = model\User::get($id);
					if( $user ) {
						$user['name'] = $user['firstname'] . ' ' . ($user['infix'] ? $user['infix'] . ' ' : '') . $user['lastname'];
						$user['moderator'] = array_column(model\Moderator::districts($id), 'id'); // what districts to moderate
						$token = model\Token::encode( $user );
						if ($token) {
							$response->getBody()->write(json_encode(['token' => $token ], JSON_UNESCAPED_SLASHES));
							return $response;
						}
					}
				}
				throw new HttpNotFoundException($request, 'User not found');
			}
			throw new HttpBadRequestException( $request, 'Bad credentials' );
		}
		throw new HttpBadRequestException( $request, 'Bad body' );
	}

	public static function newLogin( Request $request, Response $response, array $args ) : Response { // get token with query
		$body = $request->getParsedBody();
		if( $body ) {
			$email = $body[ 'email' ] ?? null;
			$password = $body[ 'password' ] ?? null;
			if( $email && $password ) {
				$id = model\User::authenticate( $email, $password );
				if( $id ) {
					$user = model\User::get($id);
					if( $user ) {
						$user['name'] = $user['firstname'] . ' ' . ($user['infix'] ? $user['infix'] . ' ' : '') . $user['lastname'];
						$user['moderator'] = array_column(model\Moderator::districts($id), 'id'); // what districts to moderate
						$token = model\Token::encode( $user );
						if ($token) {
							$response->getBody()->write(json_encode(['token' => $token ], JSON_UNESCAPED_SLASHES));
							return $response;
						}
					}
				}
				throw new HttpNotFoundException($request, 'User not found');
			}
			throw new HttpBadRequestException( $request, 'Bad credentials' );
		}
		throw new HttpBadRequestException( $request, 'Bad body' );
	}

	public static function resetMail( Request $request, Response $response, array $args ) : Response { // get token
		$email = $args['email'] ?? null;

		if( $email ) {
			$resetToken = model\Token::encode( [ 'email'=>$email ] );
			$servername = $_SERVER['SERVER_NAME'];

			$url = $servername == 'localhost' ? // create reset link depending on server
				'http://localhost:8100/#/reset?email='.$email.'&token='.$resetToken :
				'https://rgzuchtbuch.de/#/reset?email='.$email.'&token='.$resetToken;

			$success = model\Mail::sendResetMail( $request, $email, $url );
			$response->getBody()->write(json_encode(['success' => $success, 'servername'=>$servername ], JSON_UNESCAPED_SLASHES)); // servername for debug
			return $response;
		}
		throw new HttpNotFoundException( $request, "Invalid credentials");
	}

	public static function reset( Request $request, Response $response, array $args ) : Response { // get token
		$body = $request->getParsedBody();
		if( $body ) {
			$token = $body[ 'token' ] ?? null;
			$email = $body[ 'email' ] ?? null;
			$password = $body[ 'password' ] ?? null;
			if( $token && $email && $password && User::checkPassword( $password ) ) {
				$decoderToken = model\Token::decode( $token ); // the reset token
				if( $decoderToken ) {
					$user = $decoderToken[ 'user' ];
					if( $user ) {
						$tokenId = $user[ 'id' ] ?? null;
						if( $tokenId === null ) { // should not have id
							$tokenEmail = $user[ 'email' ] ?? null;
							if( $tokenEmail && $tokenEmail === $email ) {
								$success = model\User::setPassword( $email, $password );
								if ($success) {
									$user = model\User::getByEmail($email);
									if ($user) { // add additional info
										$user['fullname'] = $user['firstname'] . ' ' . ($user['infix'] ? $user['infix'] . ' ' : '') . $user['lastname'];
										$user['moderator'] = array_column(model\Moderator::districts($user['id']), 'id');
										$token = model\Token::encode($user); // the login token
										if( $token ) {
											$response->getBody()->write(json_encode(['token' => $token], JSON_UNESCAPED_SLASHES));
											return $response;
										}
										throw new HttpInternalServerErrorException( $request, 'could not create login token');
									}
									throw new HttpNotFoundException($request, 'User not found');
								}
								throw new HttpInternalServerErrorException( $request, 'could not update password');
							}
						}
					}
				}
				throw new HttpBadRequestException( $request, 'Invalid token' );
			}
			throw new HttpBadRequestException( $request, 'Bad credentials' );
		}
		throw new HttpBadRequestException( $request, 'Bad body' );
	}

	/** helpers **/
	private static function checkPassword( $password ) : bool {
		// // an a-z, A-Z, 0-9 and one that is not a-z,A-Z, 0-9
		$regex = "/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9])(?=.{8,})/";
		return preg_match( $regex, $password) === 1;
	}
}
