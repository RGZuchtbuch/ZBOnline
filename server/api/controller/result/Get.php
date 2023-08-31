<?php

namespace App\controller\result;

use App\query;
use App\controller\Controller;
use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;

class Get extends Controller
{
    public function authorized(): bool
    {
        return true;
    }

    public function process() : array
    {
        $id = $this->args['id'];
        $result = query\Result::get( $id );
        if( ! $result ) throw new HttpNotFoundException( );
        return [ 'result'=>$result ];
    }
}
