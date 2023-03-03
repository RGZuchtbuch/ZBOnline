<?php

namespace App\Controller\District;

use App\Model;
use App\Controller\Controller;
use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpNotFoundException;

class Breeders extends Controller
{
    public function authorized(): bool
    {
        // requester should be moderator of district
        $districtId = $this->args[ 'id' ];
        $moderatedDistricts = $this->requester[ 'moderator' ];
        return in_array( $districtId, $moderatedDistricts );
    }

    public function process() : array // parent with direct children
    {
        $id = $this->args['id'];
        $breeders = Model\District::breeders( $id );
        return [ 'breeders' => $breeders ];
    }
}
