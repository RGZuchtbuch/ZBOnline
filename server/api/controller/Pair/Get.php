<?php

namespace App\Controller\Pair;

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
        $pair = Query\Pair::get( $id );
        if( $pair ) {
            $pair['elders'] = Query\Elder::getForPair($pair['id']);
            $pair['lay'] = Query\Lay::getForPair($pair['id']);
            $pair['broods'] = Query\Brood::getForPair( $pair['id'] );
            foreach( $pair['broods'] as & $brood ) {
                $brood['chicks'] = Query\Chick::getForBrood( $brood['id'] );
            }
            $pair['show'] = Query\Show::getForPair( $pair['id'] );
        }
        return ['pair' => $pair];
    }
}
