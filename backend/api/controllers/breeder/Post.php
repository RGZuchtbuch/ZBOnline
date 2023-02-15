<?php

namespace App\controllers\breeder;

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
    public function authorized(? array $requester, array $args, array $query): bool
    {
        return $requester && ( count( $requester[ 'moderator' ] ) > 0 || $requester[ 'admin' ] );
    }

    public function process(Request $request, array $args, array $query) : mixed
    {
        try {
            $breeder = $this->getData($request);
            $success = false;
            if ($breeder['id'] === null) {
                $success = queries\breeder\Insert::execute(
                    $breeder[ 'name' ],
                    $breeder[ 'email' ],
                    $breeder[ 'districtId' ],
                    $breeder[ 'clubId' ],
                    $breeder[ 'start' ],
                    $breeder[ 'end' ],
                    $breeder[ 'info' ]
                );
                $breeder['id'] = Query::lastInsertId('id'); // get new id
            } else {
                $success = queries\breeder\Update::execute(
                    $breeder[ 'id' ],
                    $breeder[ 'name' ],
                    $breeder[ 'email' ],
                    $breeder[ 'districtId' ],
                    $breeder[ 'clubId' ],
                    $breeder[ 'start' ],
                    $breeder[ 'end' ],
                    $breeder[ 'info' ]
                );
            }
            if ($success) {
                return ['id' => $breeder['id']];
            }
        } catch( Exception $e) {
            throw new HttpBadRequestException($request, $e->getMessage() );
        }
        throw new HttpBadRequestException($request, 'Oops' );
    }
}
