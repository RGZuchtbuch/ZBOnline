<?php

namespace App\controller\breeder;

use App\query;
use App\controller\Controller;
use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;

class Delete extends Controller
{


    public function authorized(): bool // only admin and moderator of district
    {
        if( $this->requester && $this->args ) {
            if( $this->requester['admin'] ) return true; // admin
            $breeder = query\Breeder::get( $this->args[ 'id' ] );
            if( in_array( $breeder[ 'districtId' ], $this->requester[ 'moderator' ] ) ) return true; // moderator
        }
        return false;
    }

    public function process() : array
    {
        $id = $this->args['id'];
        $removed = query\Breeder::del( $id );
        return [ 'id'=>$removed ];
    }
}
