import api from '$lib/js/server.js';

export default class Report {
	static async query( args ) {
//		const query = toQuery( params );
		console.log( 'Load report', args );
		//const args = extractQuery( page.url );
		if( args && args.district && args.year ) { // must haves
			const response = await Promise.all( [
				api.get(  `/api/2/report`, { target:'chart', ...args }),
				api.get(  `/api/2/report`, { target:'map',   ...args }),
				api.get(  `/api/2/report`, { target:'trend', ...args }),
				api.get(  `/api/2/report`, { target:'table', ...args }),
			]);
			console.log('Promises loaded', response);

			return {
				chart:response[0].report,
				map:response[1].report,
				trend:response[2].report,
				table:response[3].report,
			};
		}
		return null;
	}
}

function toQuery( params ) {
	const query = {};
		addToQuery( query, 'district', +params.get('district') || 1 );
		addToQuery( query, 'year',     +params.get('year') || new Date().getFullYear() - 1 );
		addToQuery( query, 'group',     params.get('group') );
		addToQuery( query, 'section',  +params.get('section') );
		addToQuery( query, 'breed',    +params.get('breed'));
		addToQuery( query, 'color',    +params.get('color'));
		addToQuery( query, 'type',     +params.get('type') || 2 ); // defaults to breeders
	return query;
}
// async function getPromise( target, query ) {
// 	return api.report.get( Object.assign( { target:target }, query ) );
// }
// function extractQuery( url ) {
// 	const query = {};
// 		addToQuery( query, 'district', +page.url.searchParams.get('district') || 1 );
// 		addToQuery( query, 'year',     +page.url.searchParams.get('year') || new Date().getFullYear() - 1 );
// 		addToQuery( query, 'group',     page.url.searchParams.get('group') );
// 		addToQuery( query, 'section',  +page.url.searchParams.get('section') );
// 		addToQuery( query, 'breed',    +page.url.searchParams.get('breed'));
// 		addToQuery( query, 'color',    +page.url.searchParams.get('color'));
// 		addToQuery( query, 'type',     +page.url.searchParams.get('type') || 2 ); // defaults to breeders
// 	return query;
// }
function addToQuery( query, key, value ) {
	if( value ) query[ key ] = value; // only if > 0
}