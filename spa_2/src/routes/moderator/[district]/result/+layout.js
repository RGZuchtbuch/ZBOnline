import {page} from '$app/state';
import model from '$lib/js/model.js';
import {argsBuilder} from '$lib/js/tools.js';

export async function load( { depends, params, parent, url } ) {
	console.log( 'load district results', params );
	depends( 'result' ); // as i'm not using the svelte fetch

//	const data = await parent();

	// const args = argsBuilder.init();
	// 	argsBuilder.setString( args, url, 'district', params.district );
	// 	argsBuilder.setNumber( args, url, 'year', new Date().getFullYear() - 1 );
		// argsBuilder.setNumber( args, url, 'section', 3 ); // g&w
		// argsBuilder.setString( args, url, 'group', 'I' ); // all groups
	let districtId = +params.district;
	let year = url.searchParams.has( 'year') ? +url.searchParams.get( 'year' ) : new Date().getFullYear() - 1;
	console.log( 'LY', year );
	const response = await Promise.all( [
		model.Result.query( { district:districtId, year:year } ),
	] );

	return { year:year, results:response[0] };
}
