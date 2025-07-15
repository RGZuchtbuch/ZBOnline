import { ctx, cfg, store } from '$lib/js/store.svelte.js';
import { invalidate, invalidateAll } from '$app/navigation';
import { setArgsNumber, setArgsString } from '$lib/js/tools.js';
import model from '$lib/js/model.js';


export async function load( { depends, params, parent, url } ) {
	console.log( 'load edit results', params );
	depends( 'result' );

	let { standard } = await parent(); // wait for parents to have loaded

	const args = {};
		args.district = +params.district;
		setArgsNumber( args, url, 'year', new Date().getFullYear() - 1 );
		setArgsNumber( args, url, 'section', 3 ); // G&W
		setArgsString( args, url, 'group', 'I' );

		console.log( 'A', args );

	const response = await Promise.all( [
		model.Result.query( args ),
	] );
	const section = args.section === 9999 ? cfg.aocSection : standard.sections[ args.section ]; // aoc is special
	// odd thing, despite awaiting parents, section may end up null. Solved by adding if ctx.section in +page.js
	// order of layouts and pages unclear...
	return { year:args.year, section:section, group:args.group, results:response[0] };
}
