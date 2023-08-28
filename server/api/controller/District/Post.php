<?php

namespace App\Controller\District;

use App\Query;
use App\Controller\Controller;
use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;

class Post extends Controller
{
    public function authorized(): bool
    {
        if( $this->requester && $this->data ) {
            if( $this->requester['admin'] ) return true; // admin
            if( in_array( $this->data[ 'id' ], $this->requester[ 'moderator' ] ) ) return true; // moderator
        }
        return false;
    }

    public function process() : array
    {
        Query\Cache::del( 'results' );

        $data = $this->data;
        $id = $data[ 'id' ] ?? null;
        if( $id ) {
            Query\District::set( $data['id'], $data['name'], $data['fullname'], $data['short'], $data['latitude'], $data['longitude'], $data['level'], $data['moderatorId'], $this->requester[ 'id' ] );
        } else {
            $id = Query\District::new( $data['parentId'], $data['name'], $data['fullname'], $data['short'], $data['latitude'], $data['longitude'], $data['level'], $data['moderatorId'], $this->requester[ 'id' ] );
        }
        return ['id' => $id];
    }
}
