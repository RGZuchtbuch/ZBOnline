<?php

namespace App\routes\report;

use App\Queries;
use App\routes\Controller;
use DateTime;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

class Put extends Post {
    // use all from post with replace except for id > 0
}