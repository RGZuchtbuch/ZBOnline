import {Result} from '$lib/js/result.svelte.js';
import store from '$lib/js/store.svelte.js';
import { invalidate, invalidateAll } from '$app/navigation';


export async function load( { params, parent } ) {
	console.log( 'load edit results', params );
	//await invalidate( 'app:changed' );
	let data = await parent(); // wait for parents to have loaded
	const response = await Result.query( { district:+params.district, year:+params.year, section:+params.section, group:params.group } );
	const section = +params.section === 9999 ? store.aocSection : data.standard.sections[ +params.section ]; // aoc is special
	return { section:section, group:params.group, breeds:response.results };
}

