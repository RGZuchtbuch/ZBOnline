<?php

namespace App\controllers\color;

use App\queries;
use App\controllers\Controller;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class Get extends Controller
{
    public function authorized(?array $requester, array $args, array $query): bool
    {
        return true;
    }

    public function process(Request $request, array $args, array $query) : mixed
    {
        $colorId = $args['colorId'];
        $color = queries\Color::get( $colorId );
        if( ! $color) throw new HttpNotFoundException($request, "User not found");
        return $color;
    }
}
