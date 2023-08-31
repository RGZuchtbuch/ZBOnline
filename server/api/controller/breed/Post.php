<?php

namespace App\controller\breed;

use App\query;
use App\controller\Controller;
use App\controller\standard;

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
        $id = $data[ 'id' ] ?? null;// get it or null
        if( $id ) {
            query\Breed::set( $id, $data['name'], $data['sectionId'], $data['broodGroup'], $data['lay'], $data['layWeight'], $data['sireRing'], $data['dameRing'], $data['sireWeight'], $data['dameWeight'], $data['info'], $this->requester[ 'id' ] );
        } else {
            $id = query\Breed::new( $data['name'], $data['sectionId'], $data['broodGroup'], $data['lay'], $data['layWeight'], $data['sireRing'], $data['dameRing'], $data['sireWeight'], $data['dameWeight'], $data['info'], $this->requester[ 'id' ] );
        }
        query\Cache::del( 'standard' );
        query\Cache::del( 'results' );
        return ['id' => $id];
    }
}
