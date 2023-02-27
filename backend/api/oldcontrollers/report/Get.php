<?php

namespace App\controllers\report;

use App\queries;
use App\controllers\Controller;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class Get extends Controller
{
    public function authorized(?array $requester, array $args, array $query): bool
    {
        return true;
    }

    public function process(Request $request, array $args, array $query) : mixed
    {
        $reportId = $args['reportId'];
        $report = queries\report\Select::execute( $reportId );

        if( ! $report) throw new HttpNotFoundException($request, "Report not found");

        $report['parents'] = queries\report\parents\Select::execute( $reportId );
        $report['lay'] = queries\report\lay\Select::execute( $reportId );
        $report['broods'] = queries\report\broods\Select::execute( $reportId );
        $report['show'] = queries\report\show\Select::execute( $reportId );

        return [ 'report'=>$report ];
    }
}