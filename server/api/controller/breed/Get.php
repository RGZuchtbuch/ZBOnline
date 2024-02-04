<?php

namespace App\controller\breed;

use App\model;
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
        $breed = model\Breed::get( $id );
        if ($breed) {
            $breed['colors'] = model\Breed::colors( $id );
            return ['breed' => $breed];
        }
        throw new HttpNotFoundException( $this->request, 'Breed not found' );

    }
}
