<?php

namespace App\Controller\Breeder;

use App\Model;
use App\Controller\Controller;
use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;

class Get extends Controller
{
    private ? array $breeder = null;

    public function authorized(): bool
    {
        if( $this->requester && $this->args ) {
            $id = $this->args[ 'id' ];
            $this->breeder = Model\Breeder::get( $id ); // preget
            if( $this->breeder ) {
                if ($this->requester['admin']) return true; // admin
                if (in_array($this->breeder['districtId'], $this->requester['moderator'])) return true; // moderator
                if ($this->requester['id'] == $id) return true; // self
            } else {
                throw new HttpNotFoundException( $this->request, 'Breeeder not found' );
            }
        }
        return false;
    }

    public function process() : array
    {
        return ['breeder' => $this->breeder ]; // already got if for auth
    }

}
