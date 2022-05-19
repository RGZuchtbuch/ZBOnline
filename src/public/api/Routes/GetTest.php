<?php

namespace App\Routes;

use App\Queries;
use App\Utils\Token;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class GetTest extends Route
{

    public function isAuthorized( Request $request ): bool
    {
        return true;
    }

    public function process(Request $request, Response $response, array $args): Response
    {
        $data = $this->getData($request);
        $query = new Queries\User();
        $user = $query->execute($data['email'], $data['password']);
        if (!$user) throw new HttpNotFoundException($request, "Unknown user for credentials");


        // convert tot token if valid
        $token = Token::encode($user);

        $this->write($response, $token);

        return $response;
    }

}
