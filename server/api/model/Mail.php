<?php

namespace App\model;


use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Psr7\Request;

class Mail
{
	public static function sendResetMail( Request $request, string $email, string $url ) : bool {
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
			$mail->setFrom(MAIL_SENDER, MAIL_SENDER_NAME ); // Must be these !?
			$mail->addAddress( $email, 'RGZuchtbuch Mitglied');     //Add a recipient
			$mail->addReplyTo(MAIL_SENDER, MAIL_SENDER_NAME );

			//Content
			$mail->isHTML(true);                                  //Set email format to HTML
			$mail->Subject = 'RG Zuchtbuch, reset Passwort beantragt';
			$mail->Body    = "
                Für den RGZuchtbuch.de ist auf diese Emailadresse um ein neues Paswort gebeten.<br>
                <br>
                Benütze diesen Link: <a href='".$url."'>Neues Paswort</a> um ein neues Passwort einzugeben.<br>
                <br>
                Viel spaß im RGZuchtbuch Online 2.0<br>
                <br>
                Eelco Jannink<br> 
            ";
			$mail->AltBody = "
                Für den RGZuchtbuch.de ist auf diese Emailadresse um ein neues Paswort bebeten.\n
                \n
                Benütze diesen Link: ".$url."> um ein neues Passwort einzugeben.\n
                \n
                Viel spaß im RGZuchtbuch Online 2.0\n
                \n
                Eelco Jannink\n
            ";
			return $mail->send();
		} catch( Exception $e ) {
			throw new HttpInternalServerErrorException( $request, "mail error: ".$e->getMessage() );
		}
	}
}