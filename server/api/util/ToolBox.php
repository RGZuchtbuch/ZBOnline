<?php

namespace App\util;

class ToolBox
{

	public static function tree( array $rows ) : ? array // uses id and parentId for hierarchy
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

}