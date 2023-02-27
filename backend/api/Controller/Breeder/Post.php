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
        return Model\Breeder::authorized( $this->args[ 'id' ], $this->requester[ 'id' ] );
    }

    public function process() : array
    {
        $data = $this->data;
        $id = Model\Breeder::new($data['firstname'], $data['infix'], $data['lastname'], $data['email'], $data['districtId'], $data['clubId'], $data['start'], $data['end'], $data['info'] );
        return ['id' => $id];
    }
}
