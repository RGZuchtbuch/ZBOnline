<?php

namespace App\Controller\District;

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

    public function process() : array // parent with direct children
    {
        $id = $this->args['id'];
        $districts = Query\District::children( $id );
        $root = $this->tree( $districts );
        return [ 'district' => $root ];
    }
}
