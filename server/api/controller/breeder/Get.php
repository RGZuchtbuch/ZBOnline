<?php

namespace App\controller\breeder;

use App\query;
use App\controller\Controller;
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
            $this->breeder = query\Breeder::get($id); // preget
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
        $row = $this->breeder; // already got it for auth
        $breeder = [
            'id'=>$row['id'],
            'firstname'=>$row['firstname'],
            'infix'=>$row['infix'],
            'lastname'=>$row['lastname'],
            'district'=>[ 'id'=>$row['districtId'], 'name'=>$row['districtName'] ],
            'club'=>[ 'id'=>$row['clubId'], 'name'=>$row['clubName'] ],
            'start'=>$row['start'],
            'end'=>$row['end'],
            'email'=>$row['email'],
            'info'=> $row['info']
        ];
        return ['breeder' => $breeder ]; // already got if for auth
    }

}
