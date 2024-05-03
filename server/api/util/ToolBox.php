<?php

namespace App\util;

class ToolBox
{

	public static function toInt( $value ) { // valid int or null
		return is_numeric( $value) ? (int) $value : null;
	}

	public static function toTree( array $rows ) : ? array // uses id and parentId for hierarchy
	{
		$root = null;
		$nodes = [];
		foreach( $rows as & $row ) {
			$row[ 'children' ] = []; // all nodes can have children
			$nodes[ $row['id'] ] = & $row;
		}
		foreach( $nodes as & $child ) {
			$parentId = & $child[ 'parentId' ];
			if( $parentId && isset( $nodes[ $parentId ] ) ) { //has and exists in array then its a child
				$parent = & $nodes[ $parentId ];
				$parent[ 'children' ][] = & $child;
			} else { // must be root, should only be one
				$root = & $child;
			}
		}
		return $root;
	}


	// turns list of results into report tree ( sections → subsections → breeds → colors )
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
				unset( $breed ); // to loose ref
				$breed = [ 'id'=>$breedId, 'name'=>$row[ 'breedName' ], 'colors'=>[] ];
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