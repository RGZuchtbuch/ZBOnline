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
        $result = $this->data;
        $success = false;
        if( $this->isCleared( $result ) ) {
            $success = Query\Result::del( $result['id'] );
            $result['id'] = null; // signals deleted
        } else if( $result['id'] == null ) {
            $id = Query\Result::new(
                $result['reportId'], $result['districtId'], $result['year'], $result['group'],
                $result['breedId'], $result['colorId'],
                $result['breeders'], $result['pairs'],
                $result['layDames'], $result['layEggs'], $result['layWeight'],
                $result['broodEggs'], $result['broodFertile'], $result['broodHatched'],
                $result['showCount'], $result['showScore'], $this->requester[ 'id' ]
            );
            if( $id ) {
                $result['id'] = $id;
                $success = true;
            }
        } else {
            $success = Query\Result::set(
                $result['id'],
                $result['reportId'], $result['districtId'], $result['year'], $result['group'],
                $result['breedId'], $result['colorId'],
                $result['breeders'], $result['pairs'],
                $result['layDames'], $result['layEggs'], $result['layWeight'],
                $result['broodEggs'], $result['broodFertile'], $result['broodHatched'],
                $result['showCount'], $result['showScore'], $this->requester[ 'id' ]
            );
        }
        if ($success) {
            return [ 'id' => $result['id'] ];
        }
        throw new HttpInternalServerErrorException( $this->request, "Query result\Post failed" );
    }

    // is no relevant content then delete the result ( is id != null )
    private function isCleared( array $result ) {
        return $result['breeders'] === null && $result['pairs'] === null; // no breeders and no pairs means empty
    }
}
