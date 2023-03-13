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
        return true;;
    }

    public function process() : array
    {
        $id = $this->args['id'];
        $breed = Model\User::get( $id );
        if( ! $breed ) throw new HttpNotFoundException( );
        $breed['colors'] = Model\Breed::colors( $id );
        return [ 'Breed'=>$breed ];
    }
}
