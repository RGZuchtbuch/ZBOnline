<?php

namespace App\Controller\Breeder;

use App\Model;
use App\Controller\Controller;
use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;

class Delete extends Controller
{


    public function authorized(): bool
    {
        return Model\Breeder::authorized( $this->args[ 'id' ], $this->requester[ 'id' ] );
    }

    public function process() : array
    {
        $id = $this->args['id'];
        $removed = Model\Breeder::del( $id );
        return [ 'id'=>$removed ];
    }
}
