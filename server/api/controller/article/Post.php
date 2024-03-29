<?php

namespace App\controller\article;

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
        $id = $data[ 'id' ] ?? null;// get it or null
        if( $id ) {
            query\Article::set( $id, $data['title'], $data['html'], $this->requester[ 'id' ] );
        } else {
            $id = query\Article::new( $data['title'], $data['html'], $this->requester[ 'id' ] );
        }
        return ['id' => $id];
    }
}
