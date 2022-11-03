<?php

namespace App\controllers\report;

use App\queries;
use App\controllers\Controller;
use Psr\Http\Message\ServerRequestInterface as Request;

class Delete extends Controller
{
    public function preAuthorized( ? array &$requester, array &$args): bool
    {
        return true; //isset( $requester['isAdmin'] ) && $requester['isAdmin'];
    }

    public function process(Request $request, array $args) : mixed
    {
        //$district = $this->getData( $request );
        return 'Delete';
        //return queries\District::create( $district['parent'], $district['name'], $district['fullname'], $district['short'], $district['coordinates'] );
    }

}