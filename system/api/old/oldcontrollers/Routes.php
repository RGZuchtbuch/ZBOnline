<?php
// hold Routes for Slim
namespace App\controllers;

use Slim\App;

class Routes {
/*
    public static function registerN( App $app ) {


    }
*/
    public static function register( App $app ) {

//        $app->get( '/Breed/{id}', new Breed\Get() );
//        $app->get( '/breeds', new breeds\Get() ); // filter on section

//        $app->post( '/token', new token\Post() );


//        $app->get( '/breeder/{id}', new Breed\Get() );
//        $app->get( '/breeders', new breeders\Get() ); // filter district or Breed, color

//        $app->get( '/color/{id}', new color\Get() );
//        $app->get( '/colors', new colors\Get() ); // filter on Breed

//        $app->get( '/district/{districtId}', new district\Get() );
//        $app->get( '/districts', new districts\Get() ); // filter on moderator or parent or root (for tree), brrg tree if none

//        $app->get( '/page/{pageId}', new page\Get() );
//        $app->get( '/pages', new pages\Get() );

//        $app->get( '/report/{reportId}', new report\Get() );
//        $app->get( '/reports', new reports\Get() ); // query has filter breeder or district

//        $app->get( '/result/{id}', new result\Get() );
//        $app->get( '/results', new results\Get() ); // filter on district and/or section/Breed/color, group, year
//        $app->get( '/years/results', new years\results\Get() ); // filter on district and/or section/Breed/color, group, year
//        $app->get( '/districts/results', new districts\results\Get() ); // filter on district and/or section/Breed/color, group, year

//        $app->get( '/section/{id}', new section\Get() );
//        $app->get( '/sections', new sections\Get() ); // filter for parent or root


//        $app->get( '/standard', new standard\Get() );

// ***************************

        //$app->get( '/', new Get() )->add( new Authenticate() );

        $app->get( '/Breed/{breedId}', new breed\Get() );
        $app->get( '/Breed/{breedId}/colors', new breed\colors\Get() );

        $app->post('/breeder', new breeder\Post() );
        $app->get( '/breeder/{breederId}', new breeder\Get() );
        $app->get( '/breeder/{breederId}/reports', new breeder\reports\Get() );
        $app->get( '/breeder/{breederId}/results', new breeder\results\Get() );
        $app->get( '/breeder/{breederId}/colors', new breeder\results\Get() ); // needed ?

        $app->get( '/color/{colorId}', new color\Get() );

        $app->post( '/credentials', new user\credentials\Post() );

        $app->get( '/district/{districtId}', new district\Get() );
        $app->post('/district', new district\Post() );
        $app->get( '/district/{districtId}/year/{year}/colors', new district\results\Get() ); // not logical !
        $app->get( '/district/{districtId}/section/{sectionId}/year/{year}/group/{group}/colors/full', new district\results\full\Get() );

        $app->get( '/district/{districtId}/breeders', new district\breeders\Get() );
        $app->get( '/district/{districtId}/children', new district\children\Get() );
        $app->get( '/district/{districtId}/results/{year}', new district\results\Get() );
        $app->get( '/district/{districtId}/root', new district\root\Get() );


//        $app->get('/district/{districtId}/section/{sectionId}/year/{year}/group/{group}/results', new district\section\results\Get() );


        $app->get( '/moderator/districts', new moderator\districts\Get() );

        $app->get( '/page/{pageId}', new page\Get() );
        $app->get( '/pages', new pages\Get() );

        $app->get( '/report/{reportId}', new report\Get() );
        $app->post( '/report', new report\Post() );
        $app->delete( '/report/{reportId}', new report\Delete() );

        $app->get( '/result/Breed/{breedId}/district/{districtId}/year/{year}/group/{group}/results', new result\breed\Get() );
        $app->get( '/result/colors/{breedId}/district/{districtId}/year/{year}/group/{group}/results', new result\colors\Get() );
        $app->post('/result', new result\Post() );

        $app->get( '/results/years', new trend\Get() ); // for trend, query with section/Breed/color,  year
        $app->get( '/results/districts', new map\Get() ); // for map, , query with section/Breed/color,  year

        $app->get( '/section/{sectionId}', new section\Get() );
        $app->get( '/section/{sectionId}/children', new section\children\Get() );
        $app->get( '/section/{sectionId}/breeds', new section\breeds\Get() );
        $app->get( '/section/{sectionId}/root', new section\root\Get() );


        $app->post( '/user/token', new Controller\User\Token() );



        $app->get( '/standard', new standard\Get() );

    }
};



