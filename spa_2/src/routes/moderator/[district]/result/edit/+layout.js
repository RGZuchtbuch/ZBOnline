import { ctx, store } from '$lib/js/store.svelte.js';
import { invalidate, invalidateAll } from '$app/navigation';
import { setArgsNumber, setArgsString } from '$lib/js/tools.js';
import model from '$lib/js/model.js';


export async function load( { params, parent, url } ) {
	console.log( 'load edit results', params );
	let data = await parent(); // wait for parents to have loaded

	const args = {};
		args.district = +params.district;
		setArgsNumber( args, url, 'year', new Date().getFullYear() - 1 );
		setArgsNumber( args, url, 'section', 3 ); // G&W
		setArgsString( args, url, 'group', 'I' );

		console.log( 'A', args );

	const response = await Promise.all( [
		model.Result.query( args ),
	] );
	const section = args.section === 9999 ? store.aocSection : data.standard.sections[ args.section ]; // aoc is special

	return { year:args.year, section:section, group:args.group, results:response[0] };
}
//
// export async function load( { params, parent } ) {
// 	return {}
// }