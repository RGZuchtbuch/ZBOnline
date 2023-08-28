<?php

namespace App\Controller\Result;

use App\Query;
use App\Controller\Controller;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpInternalServerErrorException;

class Post extends Controller
{
    public function authorized(): bool
    {
        if( $this->requester && $this->data ) {
            if( $this->requester['admin'] ) return true; // admin
            if( in_array( $this->data[ 'districtId' ], $this->requester[ 'moderator' ] ) ) return true; // moderator
        }
        return false;
    }

    public function process() : array
    {
        Query\Cache::del( 'results' );

        $result = $this->data;

        if( $result['breeders'] == null ) { // delete
            if( $result['id'] != null ) { // if exists
                Query\Result::del( $result['id'] );
            }
            return ['id' => null];
        }

        if( $result['id'] != null && $result['breeders'] != null ) { // set
            if( Query\Result::set( // change
                $result['id'],
                $result['pairId'], $result['districtId'], $result['year'], $result['group'],
                $result['breedId'], $result['colorId'],
                $result['breeders'], $result['pairs'],
                $result['layDames'], $result['layEggs'], $result['layWeight'],
                $result['broodEggs'], $result['broodFertile'], $result['broodHatched'],
                $result['showCount'], $result['showScore'], $this->requester[ 'id' ]
            ) ) {
                return [ 'id' => $result['id'] ];
            }
        }

        if( $result['id'] == null && $result['breeders'] != null ) { // new
            $id = Query\Result::new(
                $result['pairId'], $result['districtId'], $result['year'], $result['group'],
                $result['breedId'], $result['colorId'],
                $result['breeders'], $result['pairs'],
                $result['layDames'], $result['layEggs'], $result['layWeight'],
                $result['broodEggs'], $result['broodFertile'], $result['broodHatched'],
                $result['showCount'], $result['showScore'], $this->requester[ 'id' ]
            );
            if( $id != null ) {
                return ['id' => $id];
            }
        }
        // if nothing worked
        throw new HttpInternalServerErrorException( $this->request, "Query result\Post failed" );
    }

}
