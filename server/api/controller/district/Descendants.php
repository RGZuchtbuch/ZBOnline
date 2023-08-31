<?php

namespace App\controller\district;

use App\query;
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
        $id = $this->args['id'];
        $districts = query\District::descendants( $id );
        $root = $this->tree( $districts );
        return [ 'district' => $root ];
    }
}
