import { error } from '@sveltejs/kit';
import { cache } from '$lib/js/store.svelte.js'

export function load( { params } ) {
	console.log( 'Load standard', cache.standard[0].name );
	const districts = []
	return {
		year:params.year,
		type:params.type,
		districts:districts }
}