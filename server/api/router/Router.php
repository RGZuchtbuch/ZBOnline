<?php
// hold Routes for Slim
namespace App\router;

use Slim\App;

class Router {

/*
    public static function registerN( App $app ) {


    }
*/
    public static function register( App $app ) {
        $app->get('/', 'App\controller\Index');

        $app->get('/article/{id}', 'App\controller\article\Get' );
        $app->get('/articles', 'App\controller\article\Articles' );
        $app->post( '/article', 'App\controller\article\Post');

        $app->get('/breed/{id}', 'App\controller\breed\Get');
        $app->get('/breed/{id}/colors', 'App\controller\breed\Colors');
        $app->post( '/breed', 'App\controller\breed\Post');


        $app->get('/breeder/{id}', 'App\controller\breeder\Get');
        $app->get('/breeder/{id}/pairs', 'App\controller\breeder\Pairs');
        $app->get('/breeder/{id}/pairs/year/{year}', 'App\controller\breeder\PairsInYear' ); // for selecting pair for parent ring in report
//        $app->get('/breeder/{id}/results', 'App\controller\breeder\Results');
        $app->post('/breeder', 'App\controller\breeder\Post');

        $app->get( '/color/{id}', 'App\controller\color\Get');
        $app->post( '/color', 'App\controller\color\Post');

        $app->get('/district/{id}', 'App\controller\district\Get' );
        $app->post('/district', 'App\controller\district\Post' );

        $app->get('/district/{id}/breeders', 'App\controller\district\Breeders' );
        $app->get('/district/{id}/children', 'App\controller\district\Children' );
        $app->get('/district/{id}/descendants', 'App\controller\district\Descendants' );
        $app->get('/district/{id}/results', 'App\controller\result\District' ); // for showing

        $app->get('/log/next', 'App\controller\log\Next' );

        $app->post('/message', 'App\controller\message\Post' );

        //options: ( breederId, year ) => get( 'api/report/options/breeder/'+breederId+'/year'+year ),
        $app->get('/pair/{id}', 'App\controller\pair\Get' ); // for selecting pair for parent ring in report
        $app->post('/pair', 'App\controller\pair\Post' );


        $app->post('/result', 'App\controller\result\Post' ); // save one result
        $app->get('/result/district/{districtId}/year/{year}', 'App\controller\result\Result'); // one result for district and year, section, breed, color, group in query

        $app->get('/results/years', 'App\controller\result\DistrictYears' ); // NEW, for trend, query with section/Breed/color,  year
        $app->get('/results/districts', 'App\controller\result\YearDistricts' ); // for map, , query with section/Breed/color,  year

        $app->get('/section/{id}', 'App\controller\section\Get' );
        $app->get('/section/{id}/children', 'App\controller\section\Children' );
        $app->get('/section/{id}/breeds', 'App\controller\section\Breeds' );
        $app->get('/section/{id}/descendants', 'App\controller\section\Descendants' );

        $app->get('/standard', 'App\controller\standard\Get' );

        $app->get('/user/reset/{email}', 'App\controller\user\Reset' );
        $app->post('/user/token', 'App\controller\user\Token' );
        $app->post('/user/password', 'App\controller\user\Password' );

        $app->get('/test', 'App\controller\Test' ); // test, query has year, district, section, breed, color and group
    }
};



