<?php
// hold Routes for Slim
namespace App\Routes;

use Slim\App;

class Router {

    public static function registerRoutes( App $app ) {
        $app->get( '/', new GetIndex() );
        $app->get( '/breed/{id}', new GetBreed() );
        $app->get( '/breed/{id}/colors', new GetBreedColors() );

        $app->get( '/breeder/{id}', new GetBreeder() );
        $app->get( '/breeder/{id}/results', new GetBreederResults() );
        $app->get( '/breeder/{id}/years', new GetBreederYears() );
        $app->get( '/breeders', new GetBreeders() );

        $app->get( '/color/{id}', new GetColor() );

        $app->get( '/district/{id}', new GetDistrict() );
        $app->get( '/district/{id}/tree', new GetDistrictTree() );
        $app->get( '/district/{id}/breeders', new GetDistrictBreeders() );

        $app->get( '/moderator/{id}/districts', new GetModeratorDistricts() );
        $app->get( '/page/{id}', new GetPage() );

        $app->get( '/section/{id}', new GetSection() );
        $app->get( '/section/{id}/tree', new GetSectionTree() );
        $app->get( '/section/{id}/children', new GetSectionChildren() );
        $app->get( '/section/{id}/breeds', new GetSectionBreeds() );



        $app->post( '/district', new PostDistrict() );
        $app->put( '/district/{id}', new PutDistrict() );
        $app->post( '/moderator', new PostModerator() );
        $app->delete( '/moderator', new DeleteModerator() );

        $app->post( '/pair/{id}', new PostPair() );
        $app->put( '/pair/{id}', new PutPair() );

        $app->post( '/token', new PostToken() );

    }
};



