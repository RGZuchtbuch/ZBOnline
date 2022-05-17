<?php
// hold Routes for Slim
namespace App\Routes;

use Slim\App;

class Router {

    public static function registerRoutes( App $app ) {
        $app->get( '/', new GetIndex() );

        $app->post( '/token', new GetToken() );


        $app->get( '/test', new GetTest() );
    }
};



