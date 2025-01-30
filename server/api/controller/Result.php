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

class Result
{
	public static function get( Request $request, Response $response, array $args ) : Response {
		$id = $args[ 'id' ] ?? null;
		if( $id ) { // specific result
			if( is_numeric( $id ) ) {
				$result = model\Result::get( $id );
				if( $result ) {
					$response->getBody()->write(json_encode([ 'result' => $result ], JSON_UNESCAPED_SLASHES));
					return $response;
				}
				throw new HttpNotFoundException($request, 'Result not found');
			}
			throw new HttpBadRequestException( $request, 'Bad id' );
		}
		throw new HttpBadRequestException( $request, 'Cannot get all results, too many' );
		// else could get all results but that's bogus
	}


	public static function post( Request $request, Response $response, array $args ) : Response
	{
		$result = $request->getParsedBody();
		if ($result) {
			$requester = new Requester($request);
			if ($requester && ($requester->isAdmin() || $requester->isModerating($result['districtId']))) { //granted
				$id = model\Result::new(
					$result['pairId'], $result['districtId'], $result['year'], $result['group'],
					$result['sectionId'], $result['breedId'], $result['colorId'], $result['aocColor'],
					$result['breeders'], $result['pairs'],
					$result['layDames'], $result['layEggs'], $result['layWeight'],
					$result['broodEggs'], $result['broodFertile'], $result['broodHatched'],
					$result['showCount'], $result['showScore'], $requester->getId()
				);
				if ($id) {
					model\Cache::del('result' ); // clear cache as results changed
					$response->getBody()->write(json_encode(['id' => $id], JSON_UNESCAPED_SLASHES));
					return $response;
				}
				throw new HttpInternalServerErrorException($request, 'Error creating new breed');
			}
			throw new HttpUnauthorizedException($request, 'Cannot do this');
		}
		throw  new HttpBadRequestException( $request, 'Missing body' );
	}

	public static function put( Request $request, Response $response, array $args ) : Response
	{
		$id = $args[ 'id' ] ?? null;
		if( is_numeric( $id ) && $id > 0 ) {
			$result = $request->getParsedBody();
			if ($result) {
				$requester = new Requester($request);
				if ($requester && ($requester->isAdmin() || $requester->isModerating($result['districtId']))) { //granted
					$success = model\Result::set( // change
						$result['id'],
						$result['pairId'], $result['districtId'], $result['year'], $result['group'],
						$result['sectionId'], $result['breedId'], $result['colorId'], $result['aocColor'],
						$result['breeders'], $result['pairs'],
						$result['layDames'], $result['layEggs'], $result['layWeight'],
						$result['broodEggs'], $result['broodFertile'], $result['broodHatched'],
						$result['showCount'], $result['showScore'], $requester->getId()
					);
					if( $success ) {
						model\Cache::del('result' ); // clear cache as results changed
						$response->getBody()->write(json_encode(['id' => (int)$id ], JSON_UNESCAPED_SLASHES));
						return $response;
					}
					throw new HttpInternalServerErrorException($request, 'Error updating result');
				}
				throw new HttpUnauthorizedException($request, 'Cannot do this');
			}
			throw  new HttpBadRequestException($request, 'Missing body');
		}
		throw  new HttpBadRequestException($request, 'Bad id');
	}

	public static function delete( Request $request, Response $response, array $args ) : Response
	{
		$id = $args[ 'id' ] ?? null;
		if( $id && $id > 0 ) {
			$result = model\Result::get( $id );
			if( $result ) {
				$requester = new Requester($request);
				if ($requester && ($requester->isAdmin() || $requester->isModerating($result['districtId']))) { //granted
					$success = model\Result::del( $id );
					if( $success ) {
						model\Cache::del('result' ); // clear cache as results changed
						$response->getBody()->write(json_encode(['success' => $success, 'id' => $id], JSON_UNESCAPED_SLASHES));
						return $response;
					}
					throw new HttpInternalServerErrorException($request, 'Error updating result');
				}
				throw new HttpUnauthorizedException($request, 'Cannot do this');
			}
			throw  new HttpBadRequestException($request, 'Result not found');
		}
		throw  new HttpBadRequestException($request, 'Bad id');
	}

	/** other getters **/

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
            $result = model\Result::getResultDistrictYear( $districtId, $year, $sectionId, $breedId, $colorId, $group );
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

            $years = model\Result::getResultsDistrictYears( $districtId, $sectionId, $breedId, $colorId, $group );
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
			$districts = model\Result::getResultsYearDistricts( $year, $sectionId, $breedId, $colorId, $group );
            $json = json_encode([ 'districts' => $districts ], JSON_UNESCAPED_SLASHES);
			$response->getBody()->write( $json );
            model\Cache::set( 'Result', $request->getUri()->getPath(), $request->getUri()->getQuery(), $json );
            return $response;
		}
		throw  new HttpBadRequestException($request, 'Bad arguments');
	}


	// new approach 2
	public static function filter( Request $request, Response $response, array $args ) : Response {
		$query = $request->getQueryParams();
		$districtId = $query['district'] ?? null;
		$year = $query['year'] ?? null;
		$colorId = $query['color'] ?? null;

		if( is_numeric( $districtId ) && is_numeric( $year ) && is_numeric( $colorId ) ) {
			$results = model\Result::forColor( $districtId, $year, $colorId );

			$formatted = [];
			foreach( $results as &$result ) {
				$formatted[] = self::formatResult( $result );
			}
			$response->getBody()->write(json_encode(['results' => $formatted], JSON_UNESCAPED_SLASHES));
			return $response;
		}
		throw new HttpBadRequestException( $request, 'Bad query' );
	}

	public static function formatResult( array &$input ) : array	{
		return [
			'id'=>$input['id'], 'pairId'=>$input['pairId'],
			'districtId'=>$input['districtId'], 'year'=>$input['year'], 'group'=>$input['group'],
			'sectionId'=>$input['sectionId'],'breedId'=>$input['breedId'], 'colorId'=>$input['colorId'],
			'aocColor'=>$input['aocColor'],
			'breeders'=>$input['breeders'], 'pairs'=>$input['pairs'],
			'lay'=> [ 'eggs'=>$input['layEggs'], 'weight'=>$input['layWeight'] ],
			'brood'=>[ 'eggs'=>$input['broodEggs'], 'fertile'=>$input['broodFertile'], 'hatched'=>$input['broodHatched'] ],
			'show'=>[ 'count'=>$input['showCount'], 'score'=>$input['showScore'] ],
		];
	}
}