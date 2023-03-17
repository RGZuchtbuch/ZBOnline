<?php

namespace App\Controller\Color;

use App\Query;
use App\Controller\Controller;
use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;

class Get extends Controller
{
    public function authorized(): bool
    {
        return true;
    }

    public function process() : mixed
    {
        $id = $this->args['id'];
        $color = Query\Color::get( $id );
        return [ 'color'=>$color ];
    }
}
