<?php

namespace App\Controller\Breed;

use App\Model;
use App\Controller\Controller;
use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;

class Get extends Controller
{
    public function authorized(): bool
    {
        return true;
    }

    public function process() : array
    {
        $id = $this->args['id'];
        $breed = Model\Breed::get( $id );
        if ($breed) {
            $breed['colors'] = Model\Breed::colors( $id );
        }
        return ['breed' => $breed];
    }
}
