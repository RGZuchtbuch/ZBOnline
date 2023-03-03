<?php

namespace App\Controller\District;

use App\Model;
use App\Controller\Controller;
use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;

class Breeders extends Controller
{
    public function authorized(): bool
    {
        if( $this->requester ) {
            if( $this->requester['admin'] ) return true; // admin
            if( in_array( $this->args[ 'id' ], $this->requester[ 'moderator' ] ) ) return true; // moderator
        }
        return false;
    }

    public function process() : array // parent with direct children
    {
        $id = $this->args['id'];
        $breeders = Model\District::breeders( $id );
        return [ 'breeders' => $breeders ];
    }
}
