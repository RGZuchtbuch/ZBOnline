<?php

namespace App\controller;

use App\model;
use App\util\Logger;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Standard
{

	public static function get( Request $request, Response $response, array $args ) : Response { // get whole standard

		Logger::log( null, $request, "Standard" ); // indicated site loads

		$json = model\Cache::get( 'standard', $request->getUri()->getPath(), $request->getUri()->getQuery() );
		if( $json ) { // in cache
            $response->getBody()->write( $json );
			return $response;
		} else {
			$sections = model\std\Section::descendants(2); // all poultry
			$breeds = model\std\Breed::get();
			$colors = model\std\Color::get();
			//print_r( 'Colors'.$colors );
			$standard = Standard::toStandardTree($sections, $breeds, $colors);
			//$standard['test'] = & $colors;
			$json = json_encode(['standard' => $standard, 'timestamp' => date('Y-m-d H:i:s')], JSON_UNESCAPED_SLASHES);
			model\Cache::set('standard', $request->getUri()->getPath(), $request->getUri()->getQuery(), $json);
			$response->getBody()->write($json);
			return $response;
		}
	}

	/** other getters **/

	/** helpers **/

	private static function toStandardTree(& $sectionsArray, & $breedsArray, & $colorsArray ) : array {
		$sections = [];
		$breeds = [];
		$colors = [];

		// prepare each section
		foreach( $sectionsArray as & $section ) { // all by id
			$section[ 'children' ] = [];
			$section[ 'breeds' ] = [];
			$sections[ $section['id'] ] = & $section;
		}
		// add to parents children
		foreach( $sectionsArray as & $section ) { // add children to parents
			$parentId = $section[ 'parentId' ];
			if( $parentId ) {
				$sections[ $parentId ]['children'][] = & $section;
			}
		}
		// add breeds to their section
		foreach( $breedsArray as & $breed ) { // add breeds to sections
			$breed[ 'colors' ] = [];
			$breeds[ $breed[ 'id' ] ] = & $breed;
			$sections[ $breed[ 'sectionId' ] ][ 'breeds' ][] = & $breed;
		}
		// arr colors to their breeds
		foreach( $colorsArray as & $color ) { // add colors to breeds
			$colors[ $color[ 'id'] ] = & $color;
			$breeds[ $color[ 'breedId' ] ][ 'colors' ][] = & $color;
		}

		return [ 'root'=>$sections[2] ]; // geflügel
	}

}