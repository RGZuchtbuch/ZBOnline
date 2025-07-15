import {page} from '$app/state';
import model from '$lib/js/model.js';

export async function load( { depends, params } ) {
	console.log( 'load district breeder', params );
	depends( 'breeder' ); // as i'm not using the svelte fetch
	depends ( 'pair' );

	const response = await Promise.all( [
		model.Pair.query( { breeder:+params.breeder} ),
	] );

	return { pairs:response[0] };
	// const breeder = await model.Breeder.load( +params.breeder );
	// const pairs =
	// return { breeder:breeder };
}
