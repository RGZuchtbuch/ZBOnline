<?php
// hold Routes for Slim
namespace App\controllers;

use App\middleware\Authenticate;
use Slim\App;

class Routes {

    public static function register( App $app ) {
        $app->get( '/', new Get() )->add( new Authenticate() );

        $app->get( '/breed/{id}', new breed\Get() );
        $app->get( '/breed/{id}/colors', new breed\colors\Get() );

        $app->get( '/breeder/{breederId}', new breeder\Get() );
        $app->get( '/breeder/{breederId}/reports', new breeder\reports\Get() );
        $app->get( '/breeder/{breederId}/results', new breeder\results\Get() );
        $app->get( '/breeder/{breederId}/colors', new breeder\results\Get() );

        $app->get( '/color/{colorId}', new color\Get() );

        $app->post( '/credentials', new user\credentials\Post() );

        $app->get( '/district/{districtId}', new district\Get() );
        $app->get( '/district/{districtId}/year/{year}/colors', new district\results\Get() );
        $app->get( '/district/{districtId}/section/{sectionId}/year/{year}/group/{group}/colors/full', new district\results\full\Get() );

        $app->get( '/district/{districtId}/breeders', new district\breeders\Get() );


        $app->get( '/moderator/{moderatorId}/districts', new moderator\districts\Get() );

        $app->get( '/report/{reportId}', new report\Get() );
        $app->post( '/report', new report\Post() );
        $app->delete( '/report/{reportId}', new report\Delete() );

        $app->get( '/result/breed/{breedId}/district/{districtId}/year/{year}/group/{group}/results', new result\breed\Get() );
        $app->get('/result/breeds/district/{districtId}/section/{sectionId}/year/{year}/group/{group}', new result\breeds\Get() );
        $app->post('/result', new result\Post() );

//        $app->get( '/template/{id}', new result\Get() );
//        $app->post( '/template', new result\Post() );

//        $app->put( '/template/{id}', new template\Put() );


        $app->get( '/section/{sectionId}', new section\Get() );
        $app->get( '/section/{sectionId}/children', new section\children\Get() );
        $app->get( '/section/{sectionId}/breeds', new section\breeds\Get() );

    }
};



