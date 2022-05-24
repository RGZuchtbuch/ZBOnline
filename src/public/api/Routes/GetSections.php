<?php

namespace App\Routes;

use App\Queries;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetSections extends Route
{
    public function preAuthorized(?array &$requester, array &$args): bool
    {
        return true;
    }

    public function process(Request $request, Response $response, array $args): Response
    {
        $rootId = $args['id'];
        $sections = Queries\Section::getChildren( $rootId );
        $root = $this->toTree( $sections );
        $this->result['sections'] = $root;
        return $response;
    }

    protected function & toTree( & $array, $idName='id', $parentName='parent', $childrenName='children' ) : array {
        $root = & $array[0];
        $values = []; // for lookup table
        foreach( $array as & $value ) { // lookup table by id
            $id = $value[ $idName ];
            $values[ $id ] = & $value;
        }
        foreach( $array as & $child ) { // build tree
            $parentId = $child[ $parentName ];
            if( isset( $parentId ) ) { // not root
                $values[$parentId][$childrenName][] = & $child;
            }
        }
        return $root;
    }
}
