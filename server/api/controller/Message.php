<?php

namespace App\controller;

use App\model;
use App\model\Requester;
use App\util\Logger;
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
		Logger::log( null, $request, null );
		$body = $request->getParsedBody();
		$to = $body['to'] ?? null;
		$name = $body['name'] ?? null;
		$email = $body['email'] ?? null;
		$subject = $body['subject'] ?? null;
		$message = $body['message'] ?? null;
		if ($to && $name && $email && $subject && $message) {
			$moderating = array_column(model\District::readForModerator($to), 'id'); // what districts to moderate
			if (count($moderating) > 0) {
				$breeder = model\Breeder::read($to);
				if ($breeder && $breeder['email']) {
					$success = Message::send($request, $breeder['email'], $email, $name, $subject, $message);
					if( $success ) {
						$response->getBody()->write(json_encode(['success' => $success], JSON_UNESCAPED_SLASHES));
						return $response;
					}
					throw new HttpInternalServerErrorException( $request, 'Could not send mail' );
				}
				throw new HttpNotFoundException($request, 'breeder not found');
			}
		}
		throw new HttpBadRequestException($request, 'Wrong data provided');
	}

	/*************** helpers ***************/

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
			$mail->setFrom( MAIL_SENDER, 'RGZuchtbuch.de' ); // Sender, look like must be MAIL_SENDER, not $from
			$mail->addAddress( $to );                                   // Add a recipient
			$mail->addReplyTo( $from, $name );							// Add reply to

			//Content
			$mail->CharSet = "UTF-8";
			$mail->isHTML(true);                                  //Set email format to HTML
			$mail->Subject = 'RGZuchtbuch: '.$subject;
			$message = 'Von '.$name.' &lt;'.$from.'&gt;'."\n\n".$message;
			$mail->Body    = str_replace( "\n", "<br>", $message );
			$mail->AltBody = str_replace( "\n", "\n\r", $message );
			return $mail->send();
		} catch( Exception $e ) {
			throw new HttpInternalServerErrorException( $request, "mail error: ".$e->getMessage() );
		}
	}
}