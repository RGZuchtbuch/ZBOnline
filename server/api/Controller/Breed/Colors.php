<?php

namespace App\Controller\Breed;

use App\Query;
use App\Controller\Controller;
use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;

class Colors extends Controller
{
    public function authorized(): bool
    {
        return true;
    }

    public function process() : array
    {
        $id = $this->args['id'];
        $colors = Query\Breed::colors( $id );
        return [ 'colors'=>$colors ];
    }
}
