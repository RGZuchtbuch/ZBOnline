<?php

namespace App\controller\district;

use App\model;
use App\controller\Controller;
use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;


// provides district detail with it's moderator details
class Get extends Controller
{
    public function authorized(): bool
    {
        return true;
    }

    public function process() : array
    {
        $id = $this->args['id'];
        $district = model\District::get( $id );
        if( $district && $district[ 'moderatorId' ] ) { // if has moderator
            $district['moderator'] = model\Moderator::get($district['moderatorId'] );
        }
        return ['district' => $district];
    }
}
