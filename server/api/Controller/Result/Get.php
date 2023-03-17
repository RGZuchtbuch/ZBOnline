<?php

namespace App\Controller\Result;

use App\Query;
use App\Controller\Controller;
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
        $result = Query\Result::get( $id );
        if( ! $result ) throw new HttpNotFoundException( );
        return [ 'result'=>$result ];
    }
}
