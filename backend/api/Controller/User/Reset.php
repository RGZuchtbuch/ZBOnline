<?php

namespace App\Controller\User;

use App\Config;
use App\Model;
use App\Controller\Controller;
//use App\Controller\User\Token;

use DateTimeImmutable;
use Firebase\JWT\JWT;
use http\Exception\InvalidArgumentException;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;

class Reset extends Controller
{


    public function authorized(): bool
    {
        return true;
    }

    public function process() : array
    {
        $email = $this->args['email'];

        if( $email ) {
            $resetToken = Token::encode( [ 'email'=>$email ] );
            $servername = $_SERVER['SERVER_NAME'];


            $url = $servername == 'localhost' ?
                'http://localhost:8100/#/reset?email='.$email.'&token='.$resetToken :
                'https://rgzuchtbuch.de/#/reset?email='.$email.'&token='.$resetToken;

            $this->sendMail( $email, $url );

            return [ 'success' => true, 'servername'=>$servername ];
        }
        throw new HttpNotFoundException( $this->request, "Invalid credentials");
    }


    public function sendMail( string $email, string $url ) {
        $mail = new PHPMailer( true );

        try {
            //Server
            $mail->SMTPDebug = SMTP::DEBUG_OFF; //SMTP::DEBUG_SERVER;   //Dis/Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = CONFIG::MAIL_SERVER;
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = CONFIG::MAIL_SENDER;                     //SMTP username
            $mail->Password   = CONFIG::MAIL_PASSWORD;                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = CONFIG::MAIL_PORT;

            //Recipients
            $mail->setFrom(Config::MAIL_SENDER, Config::MAIL_SENDER_NAME );
            $mail->addAddress($email, 'Zuchtbuch Mitglied');     //Add a recipient
            $mail->addReplyTo(Config::MAIL_SENDER, Config::MAIL_SENDER_NAME );

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'RG Zuchtbuch, neues Passwort<br><br>';
            $mail->Body    = "Benütze diesen Link: <a href='".$url."'>Neues Paswort</a> um ein neues Passwort ein zu geben.<br><br>Die complette URL: ".$url." <br><br>Viel spaß im RGZuchtbuch ";
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
        } catch( Exception $e ) {
            throw new HttpInternalServerErrorException( $this->request, "mail error: ".$e->getMessage() );
        }
    }

}