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
	public static function filter( Request $request, Response $response, array $args ) : Response
	{
//		$json = model\Cache::get( 'Report', $request->getUri()->getPath(), $request->getUri()->getQuery() );
//		if( $json ) { // in cache
//			$response->getBody()->write( $json );
//			return $response;
//		}
//		$year       = $args['year'] ?? null;

		$query          = $request->getQueryParams(); // may all be null meaning *
		$target     = $query[ 'target' ] ?? null;
		$districtId = $query[ 'district' ] ?? null;
		$year       = $query[ 'year' ] ?? null;
		$sectionId  = $query[ 'section' ] ?? null;
		$breedId    = $query[ 'breed' ] ?? null;
		$colorId    = $query[ 'color' ] ?? null;
		$group      = $query[ 'group' ] ?? null;

		$report = null;
		//$cached = null;
		//$rows = null;

		$cached = model\Cache::get('report', $request->getUri()->getPath(), $request->getUri()->getQuery());

		if( $cached ) {
			$response->getBody()->write( $cached );
			return $response;
		}

		// if no cached json
		if( $target ) {
			switch( $target ) {
				case 'chart':
					if( $districtId && $year ) { // rest is optional...
						$report = model\Report::forChart($districtId, $year, $sectionId, $breedId, $colorId, $group);
					}
					break;
				case 'map':
					if( $year ) {
						$report = [ 'districts' => model\Report::forMap($year, $sectionId, $breedId, $colorId, $group) ];
					}
					break;
				case 'trend':
					if( $districtId ) {
						$report = [ 'years' => model\Report::forTrend($districtId, $sectionId, $breedId, $colorId, $group) ];
					}
					break;
				case 'table':
					if( $districtId && $year ) {
						$rows = model\Report::forTable($districtId, $year, $group);
						$report = self::toReportTree( $rows );
					}
					break;
			}
		}
		if( $report ) {
			$json = json_encode( [ 'report' => $report ], JSON_UNESCAPED_SLASHES );
			model\Cache::set( 'report', $request->getUri()->getPath(), $request->getUri()->getQuery(), $json );
			$response->getBody()->write( $json );
			return $response;
		}
		throw  new HttpBadRequestException($request, 'Bad arguments');
	}

	public static function toReportTree( $results ) : array
	{
		$root = [ 'sections'=>[] ];
		$sectionId = 0; // last SectionId
		$subsectionId = 0; // last subSection
		$section = null;
		$subsection = null;
		$breedId = 0; // lastBreed
		$breed = null;

		foreach ($results as $row) {
			if( $row['sectionId'] !== $sectionId ) { // next section
				$sectionId = $row['sectionId'];
				unset( $section ); // to lose ref
				$section = [ 'id'=>$sectionId, 'name'=>$row['sectionName'], 'subsections'=>[] ];
				$root[ 'sections'][] = & $section; // new section array
			}
			if( $row['subsectionId'] !== $subsectionId ) { // next section
				$subsectionId = $row['subsectionId'];
				unset( $subsection ); // to lose ref
				$subsection = [ 'id'=>$subsectionId, 'name'=>$row['subsectionName'], 'breeds'=>[] ];
				$section[ 'subsections'][] = & $subsection; // new section array
			}
			if( $row[ 'breedId' ] !== $breedId ) { // next Breed
				$breedId = $row[ 'breedId' ];
				unset( $breed ); // to lose ref
				$breed = [ 'id'=>$breedId, 'name'=>$row[ 'breedName' ], 'layEggs'=>$row['layShould'], 'layWeight'=>$row['layWeightShould'], 'colors'=>[] ];
				$subsection[ 'breeds' ][] = & $breed; // new Breed array
			}
			$result = $row;
			$result['id'] = $row['resultId'];
			if( $row['colorId'] === null && $row['aocColor'] === null ) { // pigeon result for breed
				$breed[ 'result' ] = $result;
			} else { // layer or aoc
				$breed['colors'][] = [
					'id' => $row['colorId'], 'name' => $row['colorName'], 'result'=> $result
				];
			}
		}
		return $root;
	}

}