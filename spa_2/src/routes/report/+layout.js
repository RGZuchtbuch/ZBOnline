//import {page} from '$app/state';
import model from '$lib/js/model.js';
import { argsBuilder } from '$lib/js/tools.js';


export async function load( { depends, fetch, params, parent, url } ) {
	depends( 'result' );
	console.log( 'Loading Report', url.searchParams );
	const { federation } = await parent();
	const args = argsBuilder.init();
		argsBuilder.setNumber( args, url, 'district', 1 );
		argsBuilder.setNumber( args, url, 'year', new Date().getFullYear() - 1 );

		argsBuilder.setString( args, url, 'group' );
		argsBuilder.setNumber( args, url, 'section' );
		argsBuilder.setNumber( args, url, 'breed' );
		argsBuilder.setNumber( args, url, 'color' );
		argsBuilder.setNumber( args, url, 'type', 2 );



	const response = await Promise.all( [
		model.Report.query( args ),
	] );

	return {
		args:args,
		report:response[0],
	};
}
