<?php

namespace App\routes\district\results\full;

use App\Queries;
use App\routes\Controller;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class Get extends Controller
{
    public function preAuthorized(?array &$requester, array &$args): bool
    {
        return true;
    }

    public function process(Request $request, array $args) : mixed
    {
        $districtId = $args['districtId'];
        $sectionId = $args['sectionId'];
        $year = $args['year'];
        $group = $args['group'];
        return queries\District::getSectionFullResults( $districtId, $sectionId, $year, $group );
    }

}
