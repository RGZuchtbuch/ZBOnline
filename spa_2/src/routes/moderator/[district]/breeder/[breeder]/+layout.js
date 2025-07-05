import {page} from '$app/state';
import model from '$lib/js/model.js';

export async function load( { depends, params } ) {
	console.log( 'load district breeder', params );
	depends( 'breeder' ); // as i'm not using the svelte fetch
	depends ( 'pair' );

	const response = await Promise.all( [
		model.Breeder.load( +params.breeder ),
		model.Pair.query( { breeder:+params.breeder} ),
	] );

	return { breeder:response[0], pairs:response[1] };
	// const breeder = await model.Breeder.load( +params.breeder );
	// const pairs =
	// return { breeder:breeder };
}
