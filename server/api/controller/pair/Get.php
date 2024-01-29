<?php

namespace App\controller\pair;

use App\query;
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
        $pair = query\Pair::get( $id );
        if( $pair ) {
            $pair['breeder'] = query\Breeder::getName($pair['breederId']);
            $pair['elders']  = query\Elder::getForPair($pair['id']);
            $pair['lay']     = query\Lay::getForPair($pair['id']);
            $pair['broods']  = query\Brood::getForPair( $pair['id'] );
            foreach( $pair['broods'] as & $brood ) {
                $brood['chicks'] = query\Chick::getForBrood( $brood['id'] );
            }
            $pair['show'] = query\Show::getForPair( $pair['id'] );
        }
        return ['pair' => $pair];
    }
}
