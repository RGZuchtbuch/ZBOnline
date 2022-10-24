<?php
// hold Routes for Slim
namespace App\routes;

use Slim\App;

class Router {

    public static function registerRoutes( App $app ) {
        $app->get( '/', new Get() );

//        $app->get( '/breeder/{breederId}/reports', new GetBreederReports() );
//        $app->get( '/breeder/{breederId}/results', new GetBreederResults() );
//        $app->get( '/breeder/{breederId}/years', new GetBreederYears() );
//        $app->get( '/breeders', new GetBreeders() );

//        $app->get( '/district/{districtId}/tree', new GetDistrictTree() );
//        $app->get( '/district/{id}/breeders', new Get() );

//        $app->get( '/page/{id}', new GetPage() );

 //       $app->get( '/section/{id}/tree', new GetSectionTree() );



 //       $app->post( '/district', new PostDistrict() );

//        $app->post( '/moderator', new PostModerator() );
//        $app->delete( '/moderator', new DeleteModerator() );



        $app->get( '/breed/{id}', new breed\Get() );
        $app->get( '/breed/{id}/colors', new breed\colors\Get() );

        $app->get( '/breeder/{breederId}', new breeder\Get() );
        $app->get( '/breeder/{breederId}/reports', new breeder\reports\Get() );
        $app->get( '/breeder/{breederId}/results', new breeder\results\Get() );

        $app->get( '/color/{colorId}', new color\Get() );

        $app->get( '/district/{districtId}', new district\Get() );
        $app->get( '/district/{districtId}/year/{year}/results', new district\results\Get() );
        $app->get( '/district/{districtId}/section/{sectionId}/year/{year}/group/{group}/results/full', new district\results\full\Get() );

        $app->get( '/district/{districtId}/breeders', new district\breeders\Get() );


        $app->get( '/moderator/{moderatorId}/districts', new moderator\districts\Get() );

        $app->get( '/report/{reportId}', new report\Get() );
        $app->post( '/report', new report\Post() );
        $app->put( '/report', new report\Put() );
        $app->delete( '/report/{reportId}', new report\Delete() );

        $app->get('/result/district/{districtId}/section/{sectionId}/year/{year}/group/{group}', new result\selection\Get() );

        $app->get( '/template/{id}', new result\Get() );
        $app->post( '/template', new result\Post() );
//        $app->put( '/template/{id}', new template\Put() );


        $app->get( '/section/{id}', new section\Get() );
        $app->get( '/section/{id}/children', new section\children\Get() );
        $app->get( '/section/{id}/breeds', new section\breeds\Get() );





        $app->post( '/token', new PostToken() );

    }
};



