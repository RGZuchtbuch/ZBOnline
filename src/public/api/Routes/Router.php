<?php
// hold Routes for Slim
namespace App\Routes;

use Slim\App;

class Router {

    public static function registerRoutes( App $app ) {
        $app->get( '/', new GetIndex() );
        $app->get( '/breeder/{id}/results', new GetUserResults() );
        $app->get( '/breed/{id}', new GetBreed() );
        $app->get( '/color/{id}', new GetColor() );

        $app->get( '/district/{id}', new GetDistrict() );
        $app->get( '/district/{id}/tree', new GetDistrictTree() );
//      $app->get( '/district/{id}/users', new GetDistrictUsers() );

//        $app->get( '/moderator/{id}/districts', new GetModeratorDistricts() );
        $app->get( '/page/{id}', new GetPage() );

        $app->get( '/section/{id}', new GetSection() );
        $app->get( '/section/{id}/tree', new GetSectionTree() );

        $app->get( '/user/{id}', new GetUser() );
        $app->get( '/users', new GetUsers() );


        $app->post( '/district', new PostDistrict() );
        $app->put( '/district/{id}', new PutDistrict() );
        $app->post( '/moderator', new PostModerator() );
        $app->delete( '/moderator', new DeleteModerator() );

        $app->post( '/token', new PostToken() );


        $app->get( '/test', new GetTest() );
    }
};



