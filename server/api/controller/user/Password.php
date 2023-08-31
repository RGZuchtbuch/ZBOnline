<?php

namespace App\controller\user;

use App\query;
use App\controller\Controller;
//use App\controller\user\Token;
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
                $success = query\User::setPassword($email, $password);
                if ($success) {
                    $user = query\User::getByEmail($email);
                    if ($user) {
                        $user['fullname'] = $user['firstname'] . ' ' . ($user['infix'] ? $user['infix'] . ' ' : '') . $user['lastname'];
                        $user['moderator'] = array_column(query\Moderator::districts($user['id']), 'id');
                        $token = Token::encode($user);
                        return ['token' => $token];
                    }
                }
            }
        }
        throw new HttpNotFoundException( $this->request, "Invalid reset token");
    }

}
