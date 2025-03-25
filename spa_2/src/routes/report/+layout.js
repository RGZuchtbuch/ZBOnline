import api from '$lib/js/api.js';
import {page} from '$app/state';

export async function load( { params } ) {
	console.log( 'Load' );
	const args = extractQuery( page.url );
	if( args && args.district && args.year ) {
		const responses = await Promise.all( [
			getPromise('chart', args ),
			getPromise('map',   args ),
			getPromise('trend', args ),
			getPromise('table', args ),
		]);
		console.log('Promises loaded', responses);
		//query = args;
		// report = {
		// 	query: args,
		// 	chart: responses[0].report,
		// 	map:   responses[1].report,
		// 	trend: responses[2].report,
		// 	table: responses[3].report,
		// }
		return { chart:responses[0], map:responses[1], trend:responses[2], table:responses[3], query:args };
	}
	return { query:args };
}

async function getPromise( target, query ) {
	return api.report.get( Object.assign( { target:target }, query ) );
}

function extractQuery( url ) {
	const query = {};
	addToQuery( query, 'district', +page.url.searchParams.get('district') || 1 );
	addToQuery( query, 'year',     +page.url.searchParams.get('year') || new Date().getFullYear() - 1 );
	addToQuery( query, 'group',     page.url.searchParams.get('group') );
	addToQuery( query, 'section',  +page.url.searchParams.get('section') );
	addToQuery( query, 'breed',    +page.url.searchParams.get('breed'));
	addToQuery( query, 'color',    +page.url.searchParams.get('color'));
	addToQuery( query, 'type',     +page.url.searchParams.get('type') || 2 ); // defaults to breeders
	return query;
}
function addToQuery( query, key, value ) {
	if( value ) query[ key ] = value; // only if > 0
}