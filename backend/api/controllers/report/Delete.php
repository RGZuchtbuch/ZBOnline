<?php

namespace App\controllers\report;

use App\queries;
use App\controllers\Controller;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class Delete extends Controller
{
    public function preAuthorized( ? array &$requester, array &$args): bool
    {
        return true; //isset( $requester['isAdmin'] ) && $requester['isAdmin'];
    }

    public function process(Request $request, array $args) : mixed
    {
        $reportId = $args['reportId'];

        queries\Query::begin(); // start transaction
        $success =
            queries\report\Delete::execute($reportId) &&
            queries\report\parents\Delete::execute($reportId) &&
            queries\report\lay\Delete::execute($reportId) &&
            queries\report\broods\Delete::execute($reportId) &&
            queries\report\show\Delete::execute($reportId);

        if( $success ) {
            queries\Query::commit();
        } else {
            queries\Query::rollback();
        }
        return [ 'success'=>$success ];
    }

}