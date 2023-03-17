<?php

namespace App\Controller\Section;

use App\Query;
use App\Controller\Controller;
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
        $section = Query\Section::children( $id );
        return [ 'section' => $section ];
    }
}
