<?php
// hold Routes for Slim
namespace App\routes;

use Slim\App;

class Router {

    public static function registerRoutes( App $app ) {
        $app->get( '/', new Get() );

        $app->get( '/breeder/{id}/reports', new GetBreederReports() );
        $app->get( '/breeder/{id}/results', new GetBreederResults() );
        $app->get( '/breeder/{id}/years', new GetBreederYears() );
        $app->get( '/breeders', new GetBreeders() );

        $app->get( '/district/{id}/tree', new GetDistrictTree() );
//        $app->get( '/district/{id}/breeders', new Get() );

        $app->get( '/moderator/{id}/districts', new GetModeratorDistricts() );
        $app->get( '/page/{id}', new GetPage() );

        $app->get( '/section/{id}/tree', new GetSectionTree() );



        $app->post( '/district', new PostDistrict() );

        $app->post( '/moderator', new PostModerator() );
        $app->delete( '/moderator', new DeleteModerator() );



        $app->get( '/breed/{id}', new breed\Get() );
        $app->get( '/breed/{id}/colors', new breed\colors\Get() );

        $app->get( '/breeder/{id}', new breeder\Get() );

        $app->get( '/color/{id}', new color\Get() );

        $app->get( '/district/{id}', new district\Get() );
        $app->get( '/district/{id}/year/{year}/results', new district\results\Get() );
//        $app->get( '/pair/{id}', new pair\get() );
//        $app->put( '/pair/{id}', new pair\Put() );

        $app->get( '/result/{id}', new result\Get() );
        $app->post( '/result', new result\Post() );
//        $app->put( '/result/{id}', new result\Put() );


        $app->get( '/section/{id}', new section\Get() );
        $app->get( '/section/{id}/children', new section\children\Get() );
        $app->get( '/section/{id}/breeds', new section\breeds\Get() );


        $app->post( '/token', new PostToken() );

    }
};



