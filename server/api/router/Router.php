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
        $app->get(   '/', 'App\controller\Index::get');

		$app->get(   '/article', 		'App\controller\Article::get'); // index
		$app->get(   '/article/{id}', 	'App\controller\Article::get');
		$app->post(  '/article', 		'App\controller\Article::post');
		$app->put(   '/article/{id}', 	'App\controller\Article::put');
		$app->delete('/article/{id}',	'App\controller\Article::delete');


		$app->get(   '/breed', 			'App\controller\Breed::get'); // index
		$app->get(   '/breed/{id}',		'App\controller\Breed::get');
		$app->post(  '/breed', 			'App\controller\Breed::post');
		$app->put(   '/breed/{id}', 		'App\controller\Breed::put');
		$app->delete('/breed/{id}', 		'App\controller\Breed::delete');

		$app->get(   '/breed/{id}/colors', 'App\controller\Breed::colors');


		$app->get(   '/breeder', 		'App\controller\Breeder::get');
		$app->get(   '/breeder/{id}', 	'App\controller\Breeder::get');
		$app->post(  '/breeder', 		'App\controller\Breeder::post');
		$app->put(   '/breeder/{id}', 	'App\controller\Breeder::put');
		$app->delete('/breeder/{id}', 	'App\controller\Breeder::delete');


		$app->get(   '/breeder/{id}/pair', 'App\controller\Breeder::pairs');
		$app->get(   '/breeder/{id}/year/{year}/pair', 'App\controller\Breeder::yearPairs'); // USE FOR PARENT PAIR!

		// get /color not supported, too much data
		$app->get(   '/color/{id}', 		'App\controller\::get');
		$app->post(  '/color', 			'App\controller\Color::post');
		$app->put(   '/color/{id}', 		'App\controller\Color::put');
        $app->delete('/color/{id}', 		'App\controller\Color::delete');


		$app->get(   '/district',      'App\controller\District::get' );
        $app->get(   '/district/{id}', 'App\controller\District::get' );
		//$app->post(  '/district',      'App\controller\District::post' );
		$app->put(   '/district/{id}', 'App\controller\District::put' );
		// $app->delete( '/district/{id}', 'App\controller\District::delete'); // not supported yet

        $app->get(	'/district/{id}/breeders',   	'App\controller\District::breeders' );
        $app->get(	'/district/{id}/children',   	'App\controller\District::children' );
        $app->get(	'/district/{id}/descendants',	'App\controller\District::descendants' );

		// should be report/district/{id}/year/{year}
		$app->get(	'/district/{id}/year/{year}/report', 'App\controller\District::report' ); // for showing table
		$app->get(	'/report/district/{id}/year/{year}', 'App\controller\District::report' ); // for showing table

		$app->get(	'/district/{id}/results', 'App\controller\District::results' ); // having year, section and group in query. For obmann edit
		$app->get(	'/district/{id}/breed/{breed}/results', 'App\controller\District::breedResults' ); // having year, section and group in query. For obmann edit

		$app->post('/message',                'App\controller\Message::post' ); // send message to obmann

		$app->get(	 '/pair/{id}', 'App\controller\Pair::get' ); // for selecting pair for parent ring in report
        $app->post(	 '/pair', 'App\controller\Pair::post' );
        $app->put(	 '/pair/{id}', 'App\controller\Pair::post' ); // same as post, correct
		$app->delete('/pair/{id}', 'App\controller\Pair::delete' );

		$app->get(	 '/pair', 'App\controller\Pair::filter' ); // for selecting pair for parent ring in report


		$app->get('/section',                 'App\controller\Section::get' );
		$app->get('/section/{id}',            'App\controller\Section::get' );
		$app->get('/section/{id}/breeds',     'App\controller\Section::breeds' );
		$app->get('/section/{id}/children',   'App\controller\Section::children' );
		$app->get('/section/{id}/descendants','App\controller\Section::descendants' );

		$app->get('/standard', 'App\controller\Standard::get' );

/*********************/

		$app->get('/log/next', 'App\controller\Log::next' );
		$app->get('/log/clear', 'App\controller\Log::clear' );

		$app->get(	 '/result/{id}', 'App\controller\Result::get' ); // for selecting pair for parent ring in report
		$app->get(	 '/result/district/{districtId}/year/{year}', 'App\controller\Result::resultFor' ); //
		$app->get(	 '/results/years/district/{id}', 'App\controller\Result::years' ); //
		$app->get(	 '/results/districts/year/{year}', 'App\controller\Result::districts' ); //

		$app->post(	 '/result', 'App\controller\Result::post' );
		$app->put(	 '/result/{id}', 'App\controller\Result::put' ); // same as post, correct
		$app->delete('/result/{id}', 'App\controller\Result::delete' );


        $app->get('/user/reset/{email}', 'App\controller\User::resetMail' ); // sends an email with reset link
		$app->post('/user/token', 'App\controller\User::login' ); // post, as we do not want credentials in query !
		$app->post('/user/password', 'App\controller\User::reset' );

		// TODO New approach 2
		$app->post('/2/login', 'App\controller\Auth::newLogin' ); // post credentials, replies token!
		$app->post('/2/forgot', 'App\controller\Auth::newForgot' ); // post forgot password email, sends email
		$app->post('/2/reset', 'App\controller\Auth::newReset' ); // post resetToken and new password, returns loginToken

		$app->get('/2/breed', 'App\controller\Breed::filter' );
		$app->get('/2/color', 'App\controller\Color::filter' );
		$app->get('/2/result', 'App\controller\Result::filter' );


		$app->get('/test', 'App\controller\Test' ); // test, query has year, district, section, breed, color and group
    }
};



