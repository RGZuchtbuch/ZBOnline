<?php

namespace App\routes\pair;

use App\routes\Controller;
use Psr\Http\Message\ServerRequestInterface as Request;

class Post extends Controller
{
    public function preAuthorized( ? array &$requester, array &$args): bool
    {
        return true; //isset( $requester['isAdmin'] ) && $requester['isAdmin'];
    }

    public function process(Request $request, array $args) : mixed
    {
        //$district = $this->getData( $request );
        return 'Post';
        //return Queries\District::create( $district['parent'], $district['name'], $district['fullname'], $district['short'], $district['coordinates'] );
    }

}