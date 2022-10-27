<?php
declare(strict_types=1);
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

use App\routes\Router;

require __DIR__.'/vendor/autoload.php';

// allow cross origin from all
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers:  Bearer, Content-Type, Authorization, Origin');
header('Access-Control-Allow-Methods:  GET, POST, PUT, DELETE');

//ob_start('ob_gzhandler'); // needs check if header has Accept-Encoding: gzip
$app = AppFactory::create();

$app->setBasePath("/api"); // as the api lives here... else not found.

$app->addErrorMiddleware(true, true, true );

Router::registerRoutes( $app );


$app->run();

//ob_end_flush();