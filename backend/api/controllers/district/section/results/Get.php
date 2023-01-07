<?php

namespace App\controllers\district\sectionresult\breeds;

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

    // use for editing per district, not a report on districts including subs
    public function process(Request $request, array $args) : mixed
    {
        try {
            $districtId = $args['districtId'];
            $sectionId = $args['sectionId'];
            $year = $args['year'];
            $group = $args['group'];
            $breeds = queries\district\section\results\Select::execute($districtId, $sectionId, $year, $group);
//        $breeds = queries\result\breeds\Select::execute( $districtId, $sectionId, $year, $group );

            if (!$breeds) throw new HttpNotFoundException($request, "Breeds not found");

            return ['breeds' => $breeds];
        } catch( \PDOException $e ) {
            throw new \PDOException( $e->getMessage() );
        }
    }
}