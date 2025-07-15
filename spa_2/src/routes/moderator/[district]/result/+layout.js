import {page} from '$app/state';
import model from '$lib/js/model.js';
import {argsBuilder} from '$lib/js/tools.js';

export async function load( { depends, params, parent, url } ) {
	console.log( 'load district results', params );
	depends( 'result' ); // as i'm not using the svelte fetch

	let districtId = +params.district;
	let year = url.searchParams.has( 'year') ? +url.searchParams.get( 'year' ) : new Date().getFullYear() - 1;

	const response = await Promise.all( [
		model.Result.query( { district:districtId, year:year } ),
	] );

	return { year:year, results:response[0] };
}
