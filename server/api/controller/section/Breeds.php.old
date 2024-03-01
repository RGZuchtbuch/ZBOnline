<?php

namespace App\controller\section;

use App\model;
use App\controller\Controller;
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
        $sectionId = $this->args['id'];
        $breeds = model\Section::breeds( $sectionId );
        return [ 'breeds' => $breeds ];
    }
}
