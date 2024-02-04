<?php

namespace App\controller\section;

use App\model;
use App\controller\Controller;
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
        $sectionId = $this->args['id'];
        $sections = model\Section::descendants( $sectionId );
        $root = $this->tree( $sections );
        return [ 'section' => $root ];
    }
}
