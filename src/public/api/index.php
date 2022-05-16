<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__.'/vendor/autoload.php';

$app = AppFactory::create();

$app->setBasePath("/api"); // as the api lives here... els not found.

$app->addErrorMiddleware(true, false, false);

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world, should show api doc here.");
    return $response;
});

$app->run();