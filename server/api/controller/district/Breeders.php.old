<?php

namespace App\controller\district;

use App\model;
use App\controller\Controller;
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
        $rows = model\District::breeders( $id );
        $breeders = [];
        foreach( $rows as $row ) {
            $breeders[] = [
                'id'=>$row['id'],
                'firstname'=>$row['firstname'],
                'infix'=>$row['infix'],
                'lastname'=>$row['lastname'],

                'district'=>[ 'id'=>$row['districtId'], 'name'=>$row['districtName'] ],
                'club'=>[ 'id'=>$row['clubId'], 'name'=>$row['clubName'] ],
                'start'=>$row['start'],
                'end'=>$row['end']
            ];
        }


        return [ 'breeders' => $breeders, 'id'=>$id ];
    }
}
