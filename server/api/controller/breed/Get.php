<?php

namespace App\controller\breed;

use App\query;
use App\controller\Controller;
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
        $breed = query\Breed::get( $id );
        if ($breed) {
            $breed['colors'] = query\Breed::colors( $id );
            return ['breed' => $breed];
        }
        throw new HttpNotFoundException( $this->request, 'Breed not found' );

    }
}
