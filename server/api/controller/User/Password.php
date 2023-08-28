<?php

namespace App\Controller\User;

use App\Query;
use App\Controller\Controller;
//use App\Controller\User\Token;
use DateTimeImmutable;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;

class Password extends Controller
{


    public function authorized(): bool
    {
        return true;
    }

    public function process() : array
    {
        $email = $this->data['email'];
        $resetToken = $this->data['token'];
        $password = $this->data['password'];

        $decodedResetToken = Token::decode( $resetToken );
        $data = $decodedResetToken['user'] ?? null;
        if( $data ) {
            $tokenEmail = $data['email'] ?? null;
            $tokenId = $data['id'] ?? null; // should be null to distinguish from login token!
            if ( ! $tokenId && $email && $tokenEmail && $email == $tokenEmail ) {
                $success = Query\User::setPassword($email, $password);
                if ($success) {
                    $user = Query\User::getByEmail($email);
                    if ($user) {
                        $user['fullname'] = $user['firstname'] . ' ' . ($user['infix'] ? $user['infix'] . ' ' : '') . $user['lastname'];
                        $user['moderator'] = array_column(Query\Moderator::districts($user['id']), 'id');
                        $token = Token::encode($user);
                        return ['token' => $token];
                    }
                }
            }
        }
        throw new HttpNotFoundException( $this->request, "Invalid reset token");
    }

}
