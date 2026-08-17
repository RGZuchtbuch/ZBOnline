<?php
// hold Routes for Slim
namespace App\router;

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\GroupMiddleware;

class Router {

/*
    public static function registerN( App $app ) {


    }
*/
    public static function register( App $app ) {

		// TODO New approach 2
		$app->get( '/2/article', 				'App\controller\Article::filter'); // filter
		$app->get( '/2/article/{id:[0-9]+}', 	'App\controller\Article::get'); // only accept uint
		$app->post( '/2/article', 				'App\controller\Article::post');
		$app->put( '/2/article/{id:[0-9]+}', 	'App\controller\Article::put'); // only accept uint
		$app->delete( '/2/article/{id:[0-9]+}', 	'App\controller\Article::delete'); // only accept uint

		// TODO are next 3 used : should be under standard ?
		$app->get('/2/breed', 'App\controller\Breed::filter' );
		$app->get('/2/breed/{id:[0-9]+}', 'App\controller\Breed::get' );
		$app->get('/2/color', 'App\controller\Color::filter' );

		$app->get('/2/breeder', 'App\controller\Breeder::filter' );
		$app->get('/2/breeder/{id:[0-9]+}', 'App\controller\Breeder::get' );
		$app->post( '/2/breeder',  		'App\controller\Breeder::post');
		$app->put( '/2/breeder/{id:[0-9]+}','App\controller\Breeder::put');
		$app->delete( '/2/breeder/{id:[0-9]+}','App\controller\Breeder::delete');

		$app->delete( '/2/cache', 'App\controller\Cache::clear');

		$app->get('/2/district', 'App\controller\Federation::filter');
		$app->get('/2/district/{id:[0-9]+}', 'App\controller\Federation::get');
		$app->post( '/2/district', 'App\controller\Federation::post');
		$app->put( '/2/district/{id:[0-9]+}', 'App\controller\Federation::put');
		//district delete not implemented.... yet

		$app->get( '/2/log', 'App\controller\Log::filter' );
		$app->delete( '/2/log', 'App\controller\Log::clear');

		$app->post( '/2/message', 'App\controller\Message::post' );

		$app->get('/2/pair', 'App\controller\Pair::filter' );
		$app->get('/2/pair/{id:[0-9]+}', 'App\controller\Pair::read');
		$app->post( '/2/pair',  		'App\controller\Pair::post');
		$app->put( '/2/pair/{id:[0-9]+}','App\controller\Pair::put');
		$app->delete( '/2/pair/{id:[0-9]+}','App\controller\Pair::delete');

		$app->get('/2/report', 'App\controller\Report::filter' );

		$app->get('/2/result', 'App\controller\Result::filter' ); // on districtId

		$app->get('/2/result/breeder', 'App\controller\Result::getBreedersResults' ); // on districtId

		$app->get('/2/result/{id:[0-9]+}', 'App\controller\Result::read');
		$app->post( '/2/result',  		'App\controller\Result::post');
		$app->put( '/2/result/{id:[0-9]+}','App\controller\Result::put');
		$app->delete( '/2/result/{id:[0-9]+}','App\controller\Result::delete');

		$app->get( '/2/standard', 'App\controller\Standard::get' );
			$app->post( '/2/standard/breed', 'App\controller\Breed::post');
			$app->put( '/2/standard/breed/{id:[0-9]+}', 'App\controller\Breed::put');
			$app->delete( '/2/standard/breed/{id:[0-9]+}', 'App\controller\Breed::delete');

			$app->post( '/2/standard/color', 'App\controller\Color::post');
			$app->put( '/2/standard/color/{id:[0-9]+}', 'App\controller\Color::put');
			$app->delete( '/2/standard/color/{id:[0-9]+}', 'App\controller\Color::delete');

		$app->post('/2/user/login', 'App\controller\User::newLogin' ); // post credentials, replies token!
		$app->post('/2/user/forgot', 'App\controller\User::newForgot' ); // post forgot password email, sends email
		$app->post('/2/user/reset', 'App\controller\User::newReset' ); // post resetToken and new password, returns loginToken



		//$app->get('/test', 'App\controller\Test' ); // test, query has year, district, section, breed, color and group
    }
}



