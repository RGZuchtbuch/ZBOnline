<?php

namespace App\controller\breeder\old;

use App\controller\Controller;
use App\model;

class Delete extends Controller
{


    public function authorized(): bool // only admin and moderator of district
    {
        if( $this->requester && $this->args ) {
            if( $this->requester['admin'] ) return true; // admin
            $breeder = model\Breeder::get( $this->args[ 'id' ] );
            if( in_array( $breeder[ 'districtId' ], $this->requester[ 'moderator' ] ) ) return true; // moderator
        }
        return false;
    }

    public function process() : array
    {
        $id = $this->args['id'];
        $removed = model\Breeder::del( $id );
        return [ 'id'=>$removed ];
    }
}
