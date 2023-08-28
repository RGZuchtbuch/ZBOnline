<?php

namespace App\Controller\Breeder;

use App\Query;
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
        $breed = Query\User::get( $id );
        if( ! $breed ) throw new HttpNotFoundException( );
        $breed['colors'] = Query\Breed::colors( $id );
        return [ 'Breed'=>$breed ];
    }
}
