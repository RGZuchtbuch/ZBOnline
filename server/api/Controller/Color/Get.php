<?php

namespace App\Controller\Color;

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

    public function process() : mixed
    {
        $id = $this->args['id'];
        $color = Model\Color::get( $id );
        if( ! $breed ) throw new HttpNotFoundException( );
        $breed['colors'] = Model\Breed\Colors::read( $breedId );
        return [ 'Breed'=>$breed ];
    }
}
