<?php

namespace App\controllers\report;

use App\queries;
use App\controllers\Controller;
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
        $report = queries\report\Select::execute( $id );

        if( ! $report) throw new HttpNotFoundException($request, "Report not found");

        $report['parent'] = queries\Elder::getArray( $id );
        $report['lay'] = queries\Lay::get( $id );
        $report['broods'] = queries\Brood::getArray( $id );
        $report['show'] = queries\Show::get( $id );

        return [ 'report'=>$report ];
    }
}