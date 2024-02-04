<?php

namespace App\controller\section;

use App\model;
use App\controller\Controller;
use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;

class Children extends Controller
{
    public function authorized(): bool
    {
        return true;
    }

    public function process() : array
    {
        $id = $this->args['id'];
        $sections = model\Section::children( $id );
        return [ 'sections' => $sections ];
    }
}
