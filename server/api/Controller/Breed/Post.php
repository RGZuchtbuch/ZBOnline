<?php

namespace App\Controller\Breed;

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
            Query\Breed::set( $id, $data['name'], $data['sectionId'], $data['broodGroup'], $data['lay'], $data['layWeight'], $data['sireRing'], $data['dameRing'], $data['sireWeight'], $data['dameWeight'], $data['info'], $this->requester[ 'id' ] );
        } else {
            $id = Query\Breed::new( $data['name'], $data['sectionId'], $data['broodGroup'], $data['lay'], $data['layWeight'], $data['sireRing'], $data['dameRing'], $data['sireWeight'], $data['dameWeight'], $data['info'], $this->requester[ 'id' ] );
        }
        return ['id' => $id];
    }
}
