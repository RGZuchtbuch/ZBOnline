<?php

namespace App\Controller\Breed;

use App\Query;
use App\Controller\Controller;
use App\Controller\Standard;

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
            Query\Breed::set( $id, $data['name'], $data['sectionId'], $data['broodGroup'], $data['lay'], $data['layWeight'], $data['sireRing'], $data['dameRing'], $data['sireWeight'], $data['dameWeight'], $data['info'], $this->requester[ 'id' ] );
        } else {
            $id = Query\Breed::new( $data['name'], $data['sectionId'], $data['broodGroup'], $data['lay'], $data['layWeight'], $data['sireRing'], $data['dameRing'], $data['sireWeight'], $data['dameWeight'], $data['info'], $this->requester[ 'id' ] );
        }
        Query\Cache::del( 'standard' );
        Query\Cache::del( 'results' );
        return ['id' => $id];
    }
}
