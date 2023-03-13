<?php
declare(strict_types=1);
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;


require __DIR__.'/vendor/autoload.php';

// allow cross origin from all, could use Slim for this ?
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers:  Accept, Authorization, Content-Type, Origin, X-Requested-With');
header('Access-Control-Allow-Methods:  GET, POST, PUT, DELETE');
header("Content-Type: application/json");



//ob_start('ob_gzhandler'); // needs check if header has Accept-Encoding: gzip
$app = AppFactory::create();

$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true );

/*
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});
*/
$app->setBasePath("/api"); // as the api lives here... else not found.

App\Controller\Router::register( $app );
/*
$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function($req, $res) {
    $handler = $this->notFoundHandler; // handle using the default Slim page not found handler
    return $handler($req, $res);
});
*/



$app->run();

//ob_end_flush();