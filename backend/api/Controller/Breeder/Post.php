<?php

namespace App\Controller\Breeder;

use App\Model;
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
            Model\Breeder::set( $id, $data['firstname'], $data['infix'], $data['lastname'], $data['email'], $data['districtId'], $data['clubId'], $data['start'], $data['end'], $data['info'] );
        } else {
            $id = Model\Breeder::new( $data['firstname'], $data['infix'], $data['lastname'], $data['email'], $data['districtId'], $data['clubId'], $data['start'], $data['end'], $data['info'] );
        }
        return ['id' => $id];
    }
}


$id = $this->args['id'];
$data = $this->data;
if( $id === $data['id'] ) {
    $id = Model\Breeder::set($data['firstname'], $data['firstname'], $data['infix'], $data['lastname'], $data['email'], $data['districtId'], $data['clubId'], $data['start'], $data['end'], $data['info'] );
    return ['id' => $id];
}
throw new HttpBadRequestException( $this->request, "Arg id does not match data id");