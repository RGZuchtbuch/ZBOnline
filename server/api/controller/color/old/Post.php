<?php

namespace App\controller\color\old;

use App\controller\Controller;
use App\model;

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
            model\Color::set( $id, $data['name'], $data['breedId'], $data['aoc'], $data['info'], $this->requester[ 'id' ] );
        } else {
            $id = model\Color::new( $data['name'], $data['breedId'], $data['aoc'], $data['info'], $this->requester[ 'id' ] );
        }
        model\Cache::del( 'standard' );
        model\Cache::del( 'results' );
        return ['id' => $id];
    }
}
