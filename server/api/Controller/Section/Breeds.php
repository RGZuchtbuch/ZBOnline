<?php

namespace App\Controller\Section;

use App\Query;
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
        $sectionId = $this->args['id'];
        $breeds = Query\Section::breeds( $sectionId );
        return [ 'breeds' => $breeds ];
    }
}
