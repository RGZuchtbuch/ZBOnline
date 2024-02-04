<?php

namespace App\controller\log;

use App\model;
use App\controller\Controller;
use Exception;
use http\Exception\InvalidArgumentException;
use PDOException;
use Slim\Exception\HttpNotFoundException;

class Next extends Controller
{
    public function authorized(): bool
    {
        return $this->requester && $this->requester['admin'];
    }

    public function process() : array
    {
        $logs = model\Log::next( $this->query['from'], $this->query['count'] );
        return [ 'logs' => $logs ];
    }
}
