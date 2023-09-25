<?php

namespace App\controller\district;

use App\query;
use App\controller\Controller;
use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;

class Children extends Controller
{
    public function authorized(): bool
    {
        return true;
    }

    public function process() : array // parent with direct children
    {
        $id = $this->args['id'];
        $children = query\District::children( $id );
        return [ 'children' => $children ];
    }
}
