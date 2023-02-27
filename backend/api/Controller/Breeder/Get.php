<?php

namespace App\Controller\Breeder;

use App\Model;
use App\Controller\Controller;
use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;

class Get extends Controller
{


    public function authorized(): bool
    {
        return Model\Breeder::authorized( $this->args[ 'id' ], $this->requester[ 'id' ] );
    }

    public function process() : array
    {
        $id = $this->args['id'];
        $breed = Model\Breed::get( $id );
        if( ! $breed ) throw new HttpNotFoundException( );
        $breed['colors'] = Model\Breed::colors( $id );
        return [ 'Breed'=>$breed ];
    }
}
