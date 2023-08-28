<?php

namespace App\Controller\Color;

use App\Query;
use App\Controller\Controller;

class Post extends Controller
{
    public function authorized(): bool
    {
        if( $this->requester && $this->requester['admin'] ) return true; // admin
        return false;
    }

    public function process() : array
    {
        $data = $this->data;
        $id = $data[ 'id' ] ?? null;
        if( $id ) {
            Query\Color::set( $id, $data['name'], $data['breedId'], $data['aoc'], $data['info'], $this->requester[ 'id' ] );
        } else {
            $id = Query\Color::new( $data['name'], $data['breedId'], $data['aoc'], $data['info'], $this->requester[ 'id' ] );
        }
        Query\Cache::del( 'standard' );
        Query\Cache::del( 'results' );
        return ['id' => $id];
    }
}
