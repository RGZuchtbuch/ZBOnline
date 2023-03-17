<?php

namespace App\Controller\Section;

use App\Query;
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
        $sectionId = $this->args['id'];
        $sections = Query\Section::descendants( $sectionId );
        $root = $this->tree( $sections );
        return [ 'section' => $root ];
    }
}
