<?php

namespace App\Controller\Breeder;

use App\Model;
use App\Controller\Controller;
use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

class Update extends Controller
{


    public function authorized(): bool
    {
        return Model\Breeder::authorized( $this->args[ 'id' ], $this->requester[ 'id' ] );
    }

    public function process() : array
    {
        $id = $this->args['id'];
        $data = $this->data;
        if( $id === $data['id'] ) {
            $id = Model\Breeder::set($data['firstname'], $data['firstname'], $data['infix'], $data['lastname'], $data['email'], $data['districtId'], $data['clubId'], $data['start'], $data['end'], $data['info'] );
            return ['id' => $id];
        }
        throw new HttpBadRequestException( $this->request, "Arg id does not match data id");
    }
}