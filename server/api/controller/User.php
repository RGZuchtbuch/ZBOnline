<?php

namespace App\controller;

use App\model;
use App\util\Logger;
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

//	public static function login( Request $request, Response $response, array $args ) : Response { // get token
//		$body = $request->getParsedBody();
//		if( $body ) {
//			$email = $body[ 'email' ] ?? null;
//			$password = $body[ 'password' ] ?? null;
//			if( $email && $password ) {
//				$id = model\User::authenticate( $email, $password );
//				if( $id ) {
//					$user = model\User::get($id);
//					if( $user ) {
//						$user['name'] = $user['firstname'] . ' ' . ($user['infix'] ? $user['infix'] . ' ' : '') . $user['lastname'];
//						$user['moderator'] = array_column(model\District::readForModerator($id), 'id'); // what districts to moderate
//						$token = model\Token::encode( $user );
//						if ($token) {
//							$response->getBody()->write(json_encode(['token' => $token ], JSON_UNESCAPED_SLASHES));
//							return $response;
//						}
//					}
//				}
//				throw new HttpNotFoundException($request, 'User not found');
//			}
//			throw new HttpBadRequestException( $request, 'Bad credentials' );
//		}
//		throw new HttpBadRequestException( $request, 'Bad body' );
//	}

	public static function newLogin( Request $request, Response $response, array $args ) : Response { // get token with query
		$body = $request->getParsedBody();
		if( $body ) {
			$email = $body[ 'email' ] ?? null;
			$password = $body[ 'password' ] ?? null;
			Logger::log( null, null, "Login ".$email ); // cannot log $request as it has password in body
			if( $email && $password ) {
				$id = model\User::authenticate( $email, $password );
				if( $id ) {
					$user = model\User::get($id);
					if( $user ) {
						$user['name'] = $user['firstname'] . ' ' . ($user['infix'] ? $user['infix'] . ' ' : '') . $user['lastname'];
						$user['moderator'] = array_column(model\District::readForModerator($id), 'id'); // what districts to moderate
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


	public static function newForgot( Request $request, Response $response, array $args ) : Response { // get token
		Logger::log( null, $request, "Login forgotten" );
		$body = $request->getParsedBody();
		if( $body ) {

			//$email = $args['email'] ?? null;
			$email = $body[ 'email' ] ?? null;
			if ($email) {
				$resetToken = model\Token::encode(['email' => $email]);
				$servername = $_SERVER['SERVER_NAME'];

				$link = $servername == 'localhost' ? // create reset link depending on server
					'http://localhost:5173/user/reset?token=' . $resetToken : // dev server
					'https://rgzuchtbuch.de/user/reset?token=' . $resetToken; // TODO need check

				$message = "
					Für das RGZuchtbuch.de ist auf diese Emailadresse um ein neues Passwort gebeten.<br> 
					Waren Sie das, dan gehts hier weiter, sonst ignorieren Sie dieser Mail. Wenn dies öfters vorkommt, melden Sie es bitte Ihren Obmann.<br>
					<br>
					Benütze diesen Link: <a href='" . $link . "'>Neues Paswort</a> um ein neues Passwort einzugeben.<br>
					<br>
					Viel spaß im RGZuchtbuch<br>
					<br>
					Eelco Jannink<br> 
				";


				$success = Message::send($request, $email, 'admin@rgzuchtbuch.de', 'Admin', 'Passwort Link', $message );

				//$success = model\Mail::sendResetMail( $request, $email, $url );
				$response->getBody()->write(json_encode(['success' => $success, 'servername' => $servername], JSON_UNESCAPED_SLASHES)); // servername for debug
				return $response;
			}
		}
		throw new HttpNotFoundException($request, "Invalid credentials");
	}

//	public static function resetMail( Request $request, Response $response, array $args ) : Response { // get token
//		$email = $args['email'] ?? null;
//
//		if( $email ) {
//			$resetToken = model\Token::encode( [ 'email'=>$email ] );
//			$servername = $_SERVER['SERVER_NAME'];
//
//			$url = $servername == 'localhost' ? // create reset link depending on server
//				'http://localhost:8100/#/reset?email='.$email.'&token='.$resetToken : // dev server
//				'https://rgzuchtbuch.de/#/reset?email='.$email.'&token='.$resetToken;
//
//			$success = Message::send($request, $breeder['email'], $email, $name, $subject, $message);
//
//			$success = model\Mail::sendResetMail( $request, $email, $url );
//			$response->getBody()->write(json_encode(['success' => $success, 'servername'=>$servername ], JSON_UNESCAPED_SLASHES)); // servername for debug
//			return $response;
//		}
//		throw new HttpNotFoundException( $request, "Invalid credentials");
//	}

	public static function newReset( Request $request, Response $response, array $args ) : Response { // get token
		$body = $request->getParsedBody();
		if( $body ) {
			$token = $body[ 'token' ] ?? null;
//			$email = $body[ 'email' ] ?? null;
			$password = $body[ 'password' ] ?? null;
			if( $token && $password && User::checkPassword( $password ) ) { // all there and valid
				$decodedToken = model\Token::decode( $token ); // the reset token
				if( $decodedToken ) {
					$user = $decodedToken[ 'user' ]; // user with only email from resettoken
					if( $user ) {
//						$tokenId = $user[ 'id' ] ?? null; // why should i check this ?
//						if( $tokenId === null ) { // should not have id
							$tokenEmail = $user[ 'email' ] ?? null;
							if( $tokenEmail ) {
								$success = model\User::setPassword( $tokenEmail, $password );
								if ($success) {
									Logger::log( null, null, "Login reset, ok, ".$tokenEmail );
									$user = model\User::getByEmail($tokenEmail);
									if ($user) { // add additional info
										$user['fullname'] = $user['firstname'] . ' ' . ($user['infix'] ? $user['infix'] . ' ' : '') . $user['lastname'];
										$user['moderator'] = array_column(model\District::readForModerator($user['id']), 'id');
										$token = model\Token::encode($user); // the login token
										if( $token ) {
											$response->getBody()->write(json_encode(['token' => $token], JSON_UNESCAPED_SLASHES));
											return $response;
										}
										Logger::log( null, null, "Login reset, error for ".$tokenEmail );
										throw new HttpInternalServerErrorException( $request, 'could not create login token');
									}
								}
							} else {
								Logger::log( null, null, "Login reset, invalid token" );
								throw new HttpBadRequestException( $request, 'Could not update password');
							}
//						}
					}
				}
				Logger::log( null, null, "Login reset, invalid reset token" );
				throw new HttpBadRequestException( $request, 'Invalid reset token' );
			}
			Logger::log( null, null, "Login reset, bad password" );
			throw new HttpBadRequestException( $request, 'Invalid password' );
		}
		Logger::log( null, null, "Login reset, bad request" );
		throw new HttpBadRequestException( $request, 'Bad body' );
	}

//	public static function reset( Request $request, Response $response, array $args ) : Response { // get token
//		$body = $request->getParsedBody();
//		if( $body ) {
//			$token = $body[ 'token' ] ?? null;
//			$email = $body[ 'email' ] ?? null;
//			$password = $body[ 'password' ] ?? null;
//			if( $token && $email && $password && User::checkPassword( $password ) ) {
//				$decoderToken = model\Token::decode( $token ); // the reset token
//				if( $decoderToken ) {
//					$user = $decoderToken[ 'user' ];
//					if( $user ) {
//						$tokenId = $user[ 'id' ] ?? null;
//						if( $tokenId === null ) { // should not have id
//							$tokenEmail = $user[ 'email' ] ?? null;
//							if( $tokenEmail && $tokenEmail === $email ) {
//								$success = model\User::setPassword( $email, $password );
//								if ($success) {
//									$user = model\User::getByEmail($email);
//									if ($user) { // add additional info
//										$user['fullname'] = $user['firstname'] . ' ' . ($user['infix'] ? $user['infix'] . ' ' : '') . $user['lastname'];
//										$user['moderator'] = array_column(model\District::readForModerator($user['id']), 'id');
//										$token = model\Token::encode($user); // the login token
//										if( $token ) {
//											$response->getBody()->write(json_encode(['token' => $token], JSON_UNESCAPED_SLASHES));
//											return $response;
//										}
//										throw new HttpInternalServerErrorException( $request, 'could not create login token');
//									}
//									throw new HttpNotFoundException($request, 'User not found');
//								}
//								throw new HttpInternalServerErrorException( $request, 'could not update password');
//							}
//						}
//					}
//				}
//				throw new HttpBadRequestException( $request, 'Invalid token' );
//			}
//			throw new HttpBadRequestException( $request, 'Bad credentials' );
//		}
//		throw new HttpBadRequestException( $request, 'Bad body' );
//	}

	/** helpers **/
	private static function checkPassword( $password ) : bool {
		// // an a-z, A-Z, 0-9 and one that is not a-z,A-Z, 0-9
		$regex = "/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9])(?=.{8,})/";
		return preg_match( $regex, $password) === 1;
	}
}
