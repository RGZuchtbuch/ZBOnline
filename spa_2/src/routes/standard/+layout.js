import model from '$lib/js/model.js';

export async function load( { depends, fetch, params } ) {
	depends( 'standard' );
	console.log( 'Loading Standard' );
	const response = await Promise.all( [
		model.Standard.load()
	] );

	return { standard:response[0] };
}
