<?php

namespace App\controller;

use App\model;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;

class Auth
{

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
						$user['moderator'] = array_column(model\Moderator::districts($id), 'id'); // what districts to moderate
						$loginToken = model\Token::encode( $user, 1440 );// minutes
						if ($loginToken) {
							$response->getBody()->write(json_encode(['token' => $loginToken ], JSON_UNESCAPED_SLASHES));
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

	public static function newForgot( Request $request, Response $response, array $args ) : Response { // set new password
		$body = $request->getParsedBody();
		if( $body ) {
			$email = $body['email'] ?? null;
			// create token for email and send mail with link to reset page
			if ($email) {
				$servername = $_SERVER['SERVER_NAME'];
				$resetToken = model\Token::encode(['email' => $email], 60);
				$url = 'https://rgzuchtbuch.de/reset?token=' . $resetToken;
				$subject = 'RG Zuchtbuch, reset Passwort beantragt';
				$html = <<<HTML
                Für das RGZuchtbuch.de ist auf diese Emailadresse um ein neues Passwort gebeten.<br>
                <br>
                Warst du das, dann benütze diesen Link: <a href='$url'>Neues Paswort</a> um ein neues Passwort einzugeben. 
                Benütze diesen Link innerhalb von 60 Minuten<br>
                Viel spaß im RGZuchtbuch Online 2.0<br>
                <br><br>
                Das RGZuchtbuch<br>
            HTML;
				$text = <<<TEXT
				Für den RGZuchtbuch.de ist auf diese Emailadresse um ein neues Paswort bebeten.\n
				\n
				Warst du das, dann benütze diesen Link um ein neues Passwort einzugeben.\n
				\n
				$url
				\n\n 
				Benütze diesen Link innerhalb von 60 Minuten\n
				\n
				Viel spaß im RGZuchtbuch Online 2.0\n
				\n\n
				Das RGZuchtbuch\n				
			TEXT;
				$success = model\Mail::send($request, $email, $subject, $html, $text);
				$response->getBody()->write(json_encode(['success' => $success, 'servername' => $servername], JSON_UNESCAPED_SLASHES)); // servername for debug
				return $response;
			}
		}
		throw new HttpNotFoundException( $request, "Invalid request");
	}

	public static function newReset( Request $request, Response $response, array $args ) : Response
	{
		$body = $request->getParsedBody();
		if ($body) {
			$resetToken = $body['token'] ?? null;
			$password = $body['password'] ?? null;
			if ($resetToken && $password && Auth::checkPassword($password)) {
				$decoderToken = model\Token::decode($resetToken); // the reset token
				if ($decoderToken) {
					$email = $decoderToken['email'] ?? null;
					if ($email) {
						$success = model\User::setPassword($email, $password);
						if ($success) {
							$user = model\User::getByEmail($email);
							if ($user) {
								$user['moderator'] = array_column(model\Moderator::districts($user['id']), 'id');
								$loginToken = model\Token::encode($user, 1440); // login token
								if ($loginToken) {
									$response->getBody()->write(json_encode(['token' => $loginToken], JSON_UNESCAPED_SLASHES));
									return $response;
								}
							}
							throw new HttpInternalServerErrorException($request, 'could not create login token for user, password is set though');
						}
						throw new HttpNotFoundException($request, 'User not found');
					}
				}
			}
		}
		throw new HttpBadRequestException($request, 'Invalid formulated request');
	}

	/** helpers **/
	private static function checkPassword( $password ) : bool {
		// // an a-z, A-Z, 0-9 and one that is not a-z,A-Z, 0-9
		$regex = "/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9])(?=.{8,})/";
		return preg_match( $regex, $password) === 1;
	}

}
