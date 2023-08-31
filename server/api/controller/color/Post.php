<?php

namespace App\controller\color;

use App\query;
use App\controller\Controller;

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
            query\Color::set( $id, $data['name'], $data['breedId'], $data['aoc'], $data['info'], $this->requester[ 'id' ] );
        } else {
            $id = query\Color::new( $data['name'], $data['breedId'], $data['aoc'], $data['info'], $this->requester[ 'id' ] );
        }
        query\Cache::del( 'standard' );
        query\Cache::del( 'results' );
        return ['id' => $id];
    }
}
