<?php
declare(strict_types=1);
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

use App\Routes\Router;

require __DIR__.'/vendor/autoload.php';



$app = AppFactory::create();

$app->setBasePath("/api"); // as the api lives here... els not found.

$app->addErrorMiddleware(true, false, false);

Router::registerRoutes( $app );


$app->run();