<?php

namespace App\controller\district;

use App\query;
use App\controller\Controller;
use Error;
use Exception;
use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;

class Post extends Controller
{
    public function authorized(): bool
    {
        if( $this->requester && $this->data ) {
            if( $this->requester['admin'] ) return true; // admin
            if( in_array( $this->data[ 'id' ], $this->requester[ 'moderator' ] ) ) return true; // moderator
        }
        return false;
    }

    public function process() : array
    {
        query\Cache::del('results');

        $data = $this->data;
        $id = $data['id'] ?? null; // as provided or null if not defined
        $name = $data['name'] ?? null;

        if ($name) { // not null or empty
            if ($id) {
                query\District::set($data['id'], $data['name'], $data['fullname'], $data['short'], $data['latitude'], $data['longitude'], $data['level'], $data['moderatorId'], $this->requester['id']);
            } else {
                $id = query\District::new($data['parentId'], $data['name'], $data['fullname'], $data['short'], $data['latitude'], $data['longitude'], $data['level'], $data['moderatorId'], $this->requester['id']);
            }
            return ['id' => $id];
        } else {
            if( query\District::countresults( $id ) || query\District::countChildren( $id ) || query\District::countBreeders( $id ) ) {
                throw new HttpBadRequestException( $this->request, 'cannot delete district that has children or results' );
            } else {
                $deleted = query\District::del($id);
                return ['id' => null];
            }
        }
    }
}
