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
        $sections = Query\Section::children( $id );
        return [ 'sections' => $sections ];
    }
}
