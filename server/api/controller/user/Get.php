<?php

namespace App\controller\breeder;

use App\query;
use App\controller\Controller;
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
        $breed = query\User::get( $id );
        if( ! $breed ) throw new HttpNotFoundException( );
        $breed['colors'] = query\Breed::colors( $id );
        return [ 'Breed'=>$breed ];
    }
}
