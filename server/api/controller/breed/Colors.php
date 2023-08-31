<?php

namespace App\controller\breed;

use App\query;
use App\controller\Controller;
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
        $colors = query\Breed::colors( $id );
        return [ 'colors'=>$colors ];
    }
}
