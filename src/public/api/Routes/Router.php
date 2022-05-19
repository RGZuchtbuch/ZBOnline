<?php
// hold Routes for Slim
namespace App\Routes;

use Slim\App;

class Router {

    public static function registerRoutes( App $app ) {
        $app->get( '/', new GetIndex() );
        $app->get( '/user/{id}', new GetUser() );
        $app->get( '/breeder/{id}/results', new GetUserResults() );
        $app->get( '/moderator/{id}/districts', new GetModeratorDistricts() );
        $app->get( '/page/{id}', new GetPage() );

        $app->post( '/token', new GetToken() );


        $app->get( '/test', new GetTest() );
    }
};



