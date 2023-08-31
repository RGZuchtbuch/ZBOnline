<?php

namespace App\controller\message;

use App\query;
use App\controller\Controller;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use Slim\Exception\HttpInternalServerErrorException;

/**
 * Send an email to the districts moderator
 */
class Post extends Controller
{
    public function authorized(): bool
    {
        return true; // all
    }

    public function process() : array
    {
        $data = $this->data;
        if( $data['confirm'] === false ) { // simple antibot
            $district = query\District::get( $data['districtId'] );
            if ($district) {
                $moderator = query\Moderator::get($district['moderatorId']);
                if ($moderator) {
                    $success = $this->sendMail($moderator['email'], $data['from'], $data['name'], $data['subject'], $data['message']);
                    return ['success' => $success]; // servname for debug
                }
            }
        }
        return [ 'success' => false ]; // servname for debug
    }

    public function sendMail( string $to, string $from, string $name, string $subject, string $message ) : bool {
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
            throw new HttpInternalServerErrorException( $this->request, "mail error: ".$e->getMessage() );
        }
    }
}
