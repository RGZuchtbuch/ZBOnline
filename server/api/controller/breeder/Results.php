<?php

namespace App\controller\breeder;

use App\query;
use App\controller\Controller;
use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;


// TODO obsolete ?
class Results extends Controller
{
    public function authorized(): bool
    {
        if( $this->requester ) {
            if( $this->requester['admin'] ) return true; // admin
            if( count( $this->requester[ 'moderator' ] ) > 0 ) return true; // a moderator
        }
        return false;
    }

    /**
     * Handles
     *  - results for district with year for view mode 1
     *  - results per section with year and group for edit 2
     *  - results per breed with year and group for edit 3
     */
    public function process() : array // parent with direct children
    {
        $breederId = $this->args['id'] ?? null;

        $results = query\Breeder::results( $breederId );
        return [ 'results'=>$results ];
    }

}
