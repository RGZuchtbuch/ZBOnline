<?php
// hold Routes for Slim
namespace App\Router;

use Slim\App;

class Router {
/*
    public static function registerN( App $app ) {


    }
*/
    public static function register( App $app ) {
        $app->get('/', 'App\Controller\Index');

        $app->get('/article/{id}', 'App\Controller\Article\Get' );
        $app->get('/articles', 'App\Controller\Article\Articles' );
        $app->post( '/article', 'App\Controller\Article\Post');

        $app->get('/breed/{id}', 'App\Controller\Breed\Get');
        $app->get('/breed/{id}/colors', 'App\Controller\Breed\Colors');
        $app->post( '/breed', 'App\Controller\Breed\Post');


        $app->get('/breeder/{id}', 'App\Controller\Breeder\Get');
        $app->get('/breeder/{id}/results', 'App\Controller\Breeder\Results');
        $app->post('/breeder', 'App\Controller\Breeder\Post');

        $app->get( '/color/{id}', 'App\Controller\Color\Get');
        $app->post( '/color', 'App\Controller\Color\Post');

        $app->get('/district/{id}', 'App\Controller\District\Get' );
        $app->post('/district', 'App\Controller\District\Post' );

        $app->get('/district/{id}/breeders', 'App\Controller\District\Breeders' );
        $app->get('/district/{id}/children', 'App\Controller\District\Children' );
        $app->get('/district/{id}/descendants', 'App\Controller\District\Descendants' );

        $app->get('/log/next', 'App\Controller\Log\Next' );

        $app->post('/result', 'App\Controller\Result\Post' );
        $app->get('/results/years', 'App\Controller\Result\Years' ); // for trend, query with section/Breed/color,  year
        $app->get('/results/districts', 'App\Controller\Result\Districts' ); // for map, , query with section/Breed/color,  year
        $app->get('/district/{id}/results', 'App\Controller\Result\District' ); // for showing

        $app->get('/section/{id}/children', 'App\Controller\Section\Children' );
        $app->get('/section/{id}/breeds', 'App\Controller\Section\Breeds' );
        $app->get('/section/{id}/descendants', 'App\Controller\Section\Descendants' );

        $app->get('/standard', 'App\Controller\Standard\Get' );

        $app->get('/user/reset/{email}', 'App\Controller\User\Reset' );
        $app->post('/user/token', 'App\Controller\User\Token' );
        $app->post('/user/password', 'App\Controller\User\Password' );

    }
};



