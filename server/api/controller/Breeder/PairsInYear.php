<?php

namespace App\Controller\Breeder;

use App\Query;
use App\Controller\Controller;
use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;

class PairsInYear extends Controller
{
    public function authorized(): bool
    {
        if( $this->requester ) {
            if( $this->requester['admin'] ) return true; // admin
            if( count( $this->requester[ 'moderator' ] ) > 0 ) return true; // a moderator
        }
        return false;
    }

    public function process() : array // parent with direct children
    {
        $id = $this->args['id'];
        $year = $this->args['year'];
        $pairs = Query\Pair::options( $id, $year );

        return [ 'pairs' => $pairs ];
    }
}
