<?php

namespace App\routes\pair;

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
        $id = $args['id'];
        $report = Queries\Pair::get( $id );
        if( ! $report) throw new HttpNotFoundException($request, "Pair not found");

        $report['parents'] = Queries\Pair::getParents( $id );
        $report['lay'] = Queries\Pair::getLay( $id );
        $report['broods'] = Queries\Pair::getBroods( $id );
        $report['show'] = Queries\Pair::getShow( $id );
        $report['chicks'] = Queries\Pair::getChicks( $id );
        return $report;
    }
}