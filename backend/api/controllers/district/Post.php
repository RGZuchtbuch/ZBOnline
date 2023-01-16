<?php

namespace App\controllers\district;

use App\queries;
use App\queries\Query;
use App\controllers\Controller;
use Exception;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;

class Post extends Controller
{
    public function authorized(? array &$requester, array &$args): bool
    {
        return $requester && $requester[ 'admin' ];
    }

    public function process(Request $request, array $args) : mixed
    {
        try {
            $district = $this->getData($request);
            $success = false;
            if ($district['id'] === null) {
                $success = queries\district\Insert::execute(
                    $district['parentId'],
                    $district['name'], $district['fullname'], $district['short'],
                    $district['latitude'], $district['longitude']
                );
                $district['id'] = Query::lastInsertId('id'); // get new id
            } else {
                $success = queries\district\Update::execute(
                    $district['id'],
                    $district['parentId'],
                    $district['name'], $district['fullname'], $district['short'],
                    $district['latitude'], $district['longitude']
                );
            }
            if ($success) {
                return ['id' => $district['id']];
            }
        } catch( Exception $e) {
            throw new HttpBadRequestException($request, $e->getMessage() );
        }
        throw new HttpBadRequestException($request, 'Oops' );
    }
}
