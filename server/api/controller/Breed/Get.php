<?php

namespace App\Controller\Breed;

use App\Query;
use App\Controller\Controller;
use Slim\Exception\HttpNotFoundException;

class Get extends Controller
{
    public function authorized(): bool
    {
        return true;
    }

    public function process() : array // TODO should it include colors ?
    {
        $id = $this->args['id'];
        $breed = Query\Breed::get( $id );
        if ($breed) {
            $breed['colors'] = Query\Breed::colors( $id );
            return ['breed' => $breed];
        }
        throw new HttpNotFoundException( $this->request, 'Breed not found' );

    }
}
