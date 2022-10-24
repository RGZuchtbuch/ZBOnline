<?php

namespace App\routes\report;

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
        $id = $args['reportId'];
        $report = Queries\Report::get( $id );
        if( ! $report) throw new HttpNotFoundException($request, "Report not found");

        $report['parents'] = queries\Elder::getArray( $id );
        $report['lay'] = queries\Lay::get( $id );
        $report['broods'] = queries\Brood::getArray( $id );
        $report['show'] = queries\Show::get( $id );
        return [ 'report'=>$report ];
    }
}