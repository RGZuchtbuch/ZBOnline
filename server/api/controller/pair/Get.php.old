<?php

namespace App\controller\pair;

use App\model;
use App\controller\Controller;
use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;


// provides district detail with it's moderator details
class Get extends Controller
{
	public function authorized(): bool //admin, moderator
	{
		if( $this->requester ) {
			if( $this->requester['admin'] ) return true; // admin
			if( count( $this->requester[ 'moderator' ] ) > 0 ) return true; // a moderator
		}
		return false;
	}

    public function process() : array
    {
        $id = $this->args['id'];
        $pair = model\Pair::get( $id );
        if( $pair ) {
            $pair['breeder'] = model\Breeder::getName($pair['breederId']);
            $pair['elders']  = model\Elder::getForPair($pair['id']);
            $pair['lay']     = model\Lay::getForPair($pair['id']);
            $pair['broods']  = model\Brood::getForPair( $pair['id'] );
            foreach( $pair['broods'] as & $brood ) {
                $brood['chicks'] = model\Chick::getForBrood( $brood['id'] );
            }
            $pair['show'] = model\Show::getForPair( $pair['id'] );
        }
        return ['pair' => $pair];
    }
}
