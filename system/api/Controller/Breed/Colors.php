<?php

namespace App\Controller\Breed;

use App\Model;
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
        $colors = Model\Breed::colors( $id );
        return [ 'colors'=>$colors ];
    }
}
