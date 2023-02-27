<?php

namespace App\Controller\Section;

use App\Model;
use App\Controller\Controller;
use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;

class Breeds extends Controller
{
    public function authorized(): bool
    {
        return true;
    }

    public function process() : array
    {
        $id = $this->args['id'];
        $breeds = Model\Section::breeds( $id );
        return [ 'breeds' => $breeds ];
    }
}
