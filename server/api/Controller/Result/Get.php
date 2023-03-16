<?php

namespace App\Controller\Result;

use App\Model;
use App\Controller\Controller;
use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;

class Get extends Controller
{
    public function authorized(): bool
    {
        return true;
    }

    public function process() : array
    {
        $id = $this->args['id'];
        $result = Model\Result::get( $id );
        if( ! $breed ) throw new HttpNotFoundException( );
        $breed['colors'] = Model\Breed::colors( $id );
        return [ 'Breed'=>$breed ];
    }
}
