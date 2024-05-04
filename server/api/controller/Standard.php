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

class Standard
{

	public static function get( Request $request, Response $response, array $args ) : Response { // get whole standard
		$json = model\Cache::get( 'Standard', $request->getUri()->getPath(), $request->getUri()->getQuery() );
		if( $json ) { // in cache
            $response->getBody()->write( $json );
			return $response;
		}
		$sections = model\Section::getDescendants(2); // all poultry
		$breeds = model\Breed::get();
		$colors = model\Color::get();
		$standard = Standard::toStandardTree($sections, $breeds, $colors);
		$json = json_encode( [ 'standard' => $standard, 'timestamp' => date( 'Y-m-d H:i:s' ) ], JSON_UNESCAPED_SLASHES );
		$response->getBody()->write( $json );
		model\Cache::set( 'Standard', $request->getUri()->getPath(), $request->getUri()->getQuery(), $json );
        return $response;
	}

	/** other getters **/

	/** helpers **/

	private static function toStandardTree( & $sectionsRows, & $breedsRows, & $colorsRows ) : array {
		$sections = [];
		$breeds = [];

		foreach($sectionsRows as & $section ) { // all by id
			$section[ 'children' ] = [];
			$section[ 'breeds' ] = [];
			$sections[ $section['id'] ] = & $section;
		}

		foreach($sectionsRows as & $section ) { // add children to parents
			$parentId = $section[ 'parentId' ];
			if( $parentId ) {
				$sections[ $parentId ]['children'][] = & $section;
			}
		}

		foreach($breedsRows as & $breed ) { // add breeds to sections
			$breed[ 'colors' ] = [];
			$breeds[ $breed[ 'id' ] ] = & $breed;
			$sections[ $breed[ 'sectionId' ] ][ 'breeds' ][] = & $breed;
		}

		foreach($colorsRows as & $color ) { // add colors to breeds
			$breeds[ $color[ 'breedId' ] ][ 'colors' ][] = & $color;
		}

		return $sections[2]; // geflügel
	}

}