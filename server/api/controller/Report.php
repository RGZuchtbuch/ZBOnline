<?php

namespace App\controller;

use App\model;
use App\model\Requester;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;

class Report
{
	// getting one result for bar chart for a district and a year
	public static function resultFor( Request $request, Response $response, array $args ) : Response // TODO ever used ?
	{
		$json = model\Cache::get( 'Result', $request->getUri()->getPath(), $request->getUri()->getQuery() );
		if( $json ) { // in cache
			$response->getBody()->write( $json );
			return $response;
		}
		$districtId     = $args['districtId'] ?? null;
		$year           = $args['year'] ?? null;

		$query          = $request->getQueryParams();
		$sectionId  = $query[ 'section' ] ?? null;
		$breedId    = $query[ 'breed' ] ?? null;
		$colorId    = $query[ 'color' ] ?? null;
		$group      = $query[ 'group' ] ?? null;

		if( $districtId && $year && $districtId>0 && $year>0 ) {
			$result = model\Report::getResultDistrictYear( $districtId, $year, $sectionId, $breedId, $colorId, $group );
			if( $result ) {
				$json = json_encode([ 'result' => $result ], JSON_UNESCAPED_SLASHES);
				$response->getBody()->write( $json );
				model\Cache::set( 'Result', $request->getUri()->getPath(), $request->getUri()->getQuery(), $json );
				return $response;
			}
			throw new HttpNotFoundException($request, 'Result not found');
		}
		throw  new HttpBadRequestException($request, 'Bad arguments');
	}

	// for trend
	public static function years( Request $request, Response $response, array $args ) : Response
	{
		$json = model\Cache::get( 'Result', $request->getUri()->getPath(), $request->getUri()->getQuery() );
		if( $json ) { // in cache
			$response->getBody()->write( $json );
			return $response;
		}
		$districtId     = $args['id'] ?? null;

		$query          = $request->getQueryParams();
		$sectionId  = $query[ 'section' ] ?? null;
		$breedId    = $query[ 'breed' ] ?? null;
		$colorId    = $query[ 'color' ] ?? null;
		$group      = $query[ 'group' ] ?? null;

		if( $districtId && $districtId>0 ) {
			$years = model\Report::getResultsDistrictYears( $districtId, $sectionId, $breedId, $colorId, $group );
			$json = json_encode([ 'years' => $years ], JSON_UNESCAPED_SLASHES);
			$response->getBody()->write( $json );
			model\Cache::set( 'Result', $request->getUri()->getPath(), $request->getUri()->getQuery(), $json );
			return $response;
		}
		throw  new HttpBadRequestException($request, 'Bad arguments');
	}

	// for map
	public static function districts( Request $request, Response $response, array $args ) : Response
	{
		$json = model\Cache::get( 'Result', $request->getUri()->getPath(), $request->getUri()->getQuery() );
		if( $json ) { // in cache
			$response->getBody()->write( $json );
			return $response;
		}
		$year       = $args['year'] ?? null;

		$query          = $request->getQueryParams(); // may all be null meaning *
		$sectionId  = $query[ 'section' ] ?? null;
		$breedId    = $query[ 'breed' ] ?? null;
		$colorId    = $query[ 'color' ] ?? null;
		$group      = $query[ 'group' ] ?? null;

		if( $year && $year > 0 ) {
			$districts = model\Report::getResultsYearDistricts( $year, $sectionId, $breedId, $colorId, $group );
			$json = json_encode([ 'districts' => $districts ], JSON_UNESCAPED_SLASHES);
			$response->getBody()->write( $json );
			model\Cache::set( 'Result', $request->getUri()->getPath(), $request->getUri()->getQuery(), $json );
			return $response;
		}
		throw  new HttpBadRequestException($request, 'Bad arguments');
	}

}