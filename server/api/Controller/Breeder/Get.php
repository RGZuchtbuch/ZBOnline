<?php

namespace App\Controller\Breeder;

use App\Query;
use App\Controller\Controller;
use http\Exception;
use http\Exception\InvalidArgumentException;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpException;
use Slim\Exception\HttpNotFoundException;
use TypeError;

class Get extends Controller
{
    private ? array $breeder = null;

    public function authorized(): bool
    {
        if ($this->requester && $this->args) {
            $id = $this->args['id'] ?? null; // null if not exists
            $this->breeder = Query\Breeder::get($id); // preget
            if ($this->breeder) {
                if ($this->requester['admin']) return true; // admin
                if (in_array($this->breeder['districtId'], $this->requester['moderator'])) return true; // moderator
                if ($this->requester['id'] == $id) return true; // self
            } else {
                throw new HttpNotFoundException($this->request, 'Breeeder not found');
            }
        }
        return false;
    }

    public function process() : array
    {
        return ['breeder' => $this->breeder ]; // already got if for auth
    }

}
