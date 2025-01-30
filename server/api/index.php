<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Slim\Factory\AppFactory;
use App\router;

// allow cross-origin from all, could use Slim for this ?
header('Access-Control-Allow-Origin: *' );
header('Access-Control-Allow-Methods:  GET, POST, PUT, DELETE, PATCH, OPTIONS' );
header('Access-Control-Allow-Headers:  Accept, Authorization, Content-Type, Origin, X-Requested-With' );
header('Access-Control-Allow-Credentials: true' );
header('Content-Type: application/json' );

require_once './config/config.php';
require_once './vendor/autoload.php'; // require __DIR__.'/vendor/autoload.php';

ob_start('ob_gzhandler'); // needs check if header has Accept-Encoding: gzip

$app = AppFactory::create();

$app->addRoutingMiddleware();     // enable router
$app->addBodyParsingMiddleware(); // for request->getParsedBody

// Add Error Middleware depending on dev or production
if( $_SERVER[ 'REMOTE_ADDR' ] === '::1' ) { // localhost
	$errorMiddleware = $app->addErrorMiddleware(true, true, true);
} else {
	$errorMiddleware = $app->addErrorMiddleware(false, true, true);
}

/* TODO does not work as replace for lines 7-10
$app->add(function ($req, $res, $next) {
	$response = $next($req, $res);
	return $response
		->withHeader('Access-Control-Allow-Origin', '*')
		->withHeader('Access-Control-Allow-Headers', 'Accept, Authorization, Content-Type, Origin, X-Requested-With')
		->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
		->withHeader('Content-Type', 'application/json' );
});
*/

$app->setBasePath("/api"); // as the api lives here... else not found.

App\router\Router::register( $app );

$app->run();

ob_end_flush(); // for zip