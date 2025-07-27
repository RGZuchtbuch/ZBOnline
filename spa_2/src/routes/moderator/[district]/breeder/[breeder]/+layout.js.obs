import {page} from '$app/state';
import model from '$lib/js/model.js';

export async function load( { depends, params } ) {
	console.log( 'load district breeder', params );
	depends( 'breeder' ); // as i'm not using the svelte fetch

	const response = await Promise.all( [
		model.Breeder.load( +params.breeder ),
	] );

	return { breeder:response[0] };
}
