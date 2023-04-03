<?php

namespace App\Controller\Breeder;

use App\Query;
use App\Controller\Controller;
use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;

class Post extends Controller
{
    public function authorized(): bool
    {
        if( $this->requester && $this->data ) {
            if( $this->requester['admin'] ) return true; // admin
            if( in_array( $this->data[ 'districtId' ], $this->requester[ 'moderator' ] ) ) return true; // moderator
        }
        return false;
    }

    public function process() : array
    {
        $data = $this->data;
        $id = $data[ 'id' ] ?? null;
        if( $id ) {
            Query\Breeder::set( $id, $data['firstname'], $data['infix'], $data['lastname'], $data['email'], $data['clubId'], $data['start'], $data['end'], $data['info'], $this->requester[ 'id' ] ); // note districtId and id do not change
        } else {
            $id = Query\Breeder::new( $data['firstname'], $data['infix'], $data['lastname'], $data['email'], $data['districtId'], $data['clubId'], $data['start'], $data['end'], $data['info'], $this->requester[ 'id' ] );
        }
        return ['id' => $id];
    }
}
