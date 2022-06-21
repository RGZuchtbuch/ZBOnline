<?php
namespace App\Routes;

use App\Queries;
use App\Utils\Token;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class PostToken extends Route
{

    public function preAuthorized( ? array & $requester, array & $args ) : bool {
        return true;
    }

    public function process( Request $request, array $args ) : mixed
    {
        $data = $this->getData( $request );
        $user = Queries\User::login( $data['email'], $data['password'] );

        if( ! $user ) throw new HttpNotFoundException( $request, "Unknown user for credentials" );

        return Token::encode( $user );
    }

    public function postAuthorized( ? array & $requester, array & $args, array & $result ) : bool {
        return true;
    }
}