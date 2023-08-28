<?php

namespace App\Controller\District;

use App\Query;
use App\Controller\Controller;
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
        $district = Query\District::get( $id );
        if( $district && $district[ 'moderatorId' ] ) { // if has moderator
            $district['moderator'] = Query\Moderator::get($district['moderatorId'] );
        }
        return ['district' => $district];
    }
}
