<?php

namespace App\Controller\Breeder;

use App\Query;
use App\Controller\Controller;
use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

class Results extends Controller
{
    public function authorized(): bool
    {
        return true;
    }

    /**
     * Handles
     *  - results for district with year for view mode 1
     *  - results per section with year and group for edit 2
     *  - results per breed with year and group for edit 3
     */
    public function process() : array // parent with direct children
    {
        $breederId = $this->args['id'] ?? null;

        $results = Query\Breeder::results( $breederId );
        return [ 'results'=>$results ];
    }

}
