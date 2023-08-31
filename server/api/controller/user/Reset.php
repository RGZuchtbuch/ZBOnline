<?php

namespace App\controller\user;

use App\query;
use App\controller\Controller;
//use App\controller\user\Token;

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

            $success = $this->sendMail( $email, $url );

            return [ 'success' => $success, 'servername'=>$servername ]; // servname for debug
        }
        throw new HttpNotFoundException( $this->request, "Invalid credentials");
    }


    public function sendMail( string $email, string $url ) : bool {
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
            throw new HttpInternalServerErrorException( $this->request, "mail error: ".$e->getMessage() );
        }
    }

}