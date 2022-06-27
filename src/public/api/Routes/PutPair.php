<?php

namespace App\Routes;

use App\Queries;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PutPair extends Route
{
    public function preAuthorized( ? array &$requester, array &$args): bool
    {
        return true; // must be admin, moderator or breeder self
    }

    public function process(Request $request, array $args) : mixed
    {
        if ( $args['id'] > 0 ){
            $pair = $this->getData($request);
            // needs several queries, so enable rollback
            $id = Queries\Pair::update( $pair['id'], $pair['breederId'], $pair['name'], $pair['year'], $pair['group'], $pair['breed']['sectionId'], $pair['breed']['breedId'], $pair['breed']['colorId'], $pair['paired'], $pair['notes'] );

            return $id;
        }
        return null;
    }


    private function validate( array $pair ) : bool {
        return $this->validatePair($pair);
    }

    private function validatePair( array $pair ) : bool
    {
        return true;
    }

}
