<?php

namespace App\Controller\Log;

use App\Query;
use App\Controller\Controller;
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
        $logs = Query\Log::next( $this->query['from'], $this->query['count'] );
        return [ 'logs' => $logs ];
    }
}
