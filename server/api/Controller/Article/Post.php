<?php

namespace App\Controller\Article;

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
        $id = $data[ 'id' ] ?? null;// get it or null
        if( $id ) {
            Query\Article::set( $id, $data['title'], $data['html'], $this->requester[ 'id' ] );
        } else {
            $id = Query\Article::new( $data['title'], $data['html'], $this->requester[ 'id' ] );
        }
        return ['id' => $id];
    }
}
