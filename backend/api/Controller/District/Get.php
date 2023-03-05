<?php

namespace App\Controller\District;

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
        $district = Model\District::get( $id );
        if( $district && $district[ 'moderatorId' ] ) { // if has moderator
            $district['moderator'] = Model\Moderator::get($district['moderatorId'] );
        }
        return ['district' => $district];
    }
}
