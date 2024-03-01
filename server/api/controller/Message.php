<?php

namespace App\controller;

use App\model;
use App\model\Requester;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;

class Message
{
	public static function post( Request $request, Response $response, array $args ) : Response
	{
//		$requester = new Requester($request);
//		if ($requester->isUser()) {
			$body = $request->getParsedBody();
			if ($body) {
				if ($body['confirm'] === false) {
					$districtId = $body['districtId'];
					$district = model\District::get($districtId);
					if ($district) {
						$moderator = model\User::get($district['moderatorId']);
						if ($moderator) {
							$success = Message::send( $request, $moderator['email'], $body['from'], $body['name'], $body['subject'], $body['message']);
							$response->getBody()->write(json_encode(['success' => $success], JSON_UNESCAPED_SLASHES));
							return $response;
						}
						throw new HttpInternalServerErrorException( $request, 'Moderator not found' );
					}
					throw new HttpNotFoundException( $request, 'district not found');
				}
				throw new HttpNotFoundException( $request, 'assuming robot' );
			}
			throw new HttpBadRequestException( $request, 'Missing body' );
//		}
//		throw new HttpUnauthorizedException( $request, ' no way' );
	}

	/** other getters **/
	public static function send( Request $request, string $to, string $from, string $name, string $subject, string $message ) : bool {
		$mail = new PHPMailer( true );

		try {
			//Server
			$mail->SMTPDebug = SMTP::DEBUG_OFF; //SMTP::DEBUG_SERVER;   //Dis/Enable verbose debug output
			$mail->isSMTP();                                            //Send using SMTP
			$mail->Host       = MAIL_SERVER;
			$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
			$mail->Username   = MAIL_USER;                              //SMTP username
			$mail->Password   = MAIL_PASSWORD;                          //SMTP password
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
			$mail->Port       = MAIL_PORT;

			//Recipients
			$mail->setFrom( MAIL_SENDER );                       // Sender, look like must be MAIL_SENDER, not $from
			$mail->addAddress( $to );                                   //Add a recipient
			$mail->addReplyTo( $from, $name );

			//Content
			$mail->isHTML(true);                                  //Set email format to HTML
			$mail->Subject = 'RGZ: '.$subject;
			$mail->Body    = str_replace( "\n", "<br>", $message );
			$mail->AltBody = str_replace( "\n", "\n\r", $message );
			return $mail->send();
		} catch( Exception $e ) {
			throw new HttpInternalServerErrorException( $request, "mail error: ".$e->getMessage() );
		}
	}

}