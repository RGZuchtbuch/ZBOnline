<?php
// hold Routes for Slim
namespace App\controllers;

use Slim\App;

class Routes {

    public static function register( App $app ) {
        //$app->get( '/', new Get() )->add( new Authenticate() );

        $app->get( '/breed/{breedId}', new breed\Get() );
        $app->get( '/breed/{breedId}/colors', new breed\colors\Get() );

        $app->get( '/breeder/{breederId}', new breeder\Get() );
        $app->get( '/breeder/{breederId}/reports', new breeder\reports\Get() );
        $app->get( '/breeder/{breederId}/results', new breeder\results\Get() );
        $app->get( '/breeder/{breederId}/colors', new breeder\results\Get() );

        $app->get( '/color/{colorId}', new color\Get() );

        $app->post( '/credentials', new user\credentials\Post() );

        $app->get( '/district/{districtId}', new district\Get() );
        $app->post('/district', new district\Post() );
        $app->get( '/district/{districtId}/year/{year}/colors', new district\results\Get() );
        $app->get( '/district/{districtId}/section/{sectionId}/year/{year}/group/{group}/colors/full', new district\results\full\Get() );

        $app->get( '/district/{districtId}/breeders', new district\breeders\Get() );
        $app->get( '/district/{districtId}/children', new district\children\Get() );
        $app->get( '/district/{districtId}/results/{year}', new district\results\Get() );
        $app->get( '/district/{districtId}/root', new district\root\Get() );


//        $app->get('/district/{districtId}/section/{sectionId}/year/{year}/group/{group}/results', new district\section\results\Get() );

        $app->get( '/map', new map\Get() );

        $app->get( '/moderator/districts', new moderator\districts\Get() );

        $app->get( '/page/{pageId}', new page\Get() );
        $app->get( '/pages', new pages\Get() );

        $app->get( '/report/{reportId}', new report\Get() );
        $app->post( '/report', new report\Post() );
        $app->delete( '/report/{reportId}', new report\Delete() );

        $app->get( '/result/breed/{breedId}/district/{districtId}/year/{year}/group/{group}/results', new result\breed\Get() );
        $app->post('/result', new result\Post() );

        $app->get( '/section/{sectionId}', new section\Get() );
        $app->get( '/section/{sectionId}/children', new section\children\Get() );
        $app->get( '/section/{sectionId}/breeds', new section\breeds\Get() );
        $app->get( '/section/{sectionId}/root', new section\root\Get() );

        $app->get( '/trend', new trend\Get() );
    }
};



