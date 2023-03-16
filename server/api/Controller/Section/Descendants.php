<?php

namespace App\Controller\Section;

use App\Model;
use App\Controller\Controller;
use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;

class Descendants extends Controller
{
    public function authorized(): bool
    {
        return true;
    }

    public function process() : array // parent with direct children
    {
        $id = $this->args['id'];
        $sections = Model\Section::descendants( $id );
        $root = $this->tree( $sections );
        return [ 'section' => $root ];
    }
}
