import api from '$lib/js/server.js';

export class Report {
	static async load( params ) {
		const query = toQuery( params );
		console.log( 'Load report', query );
		//const args = extractQuery( page.url );
		if( query && query.district && query.year ) {
			const data = await Promise.all( [
				api.get(  `/api/2/report`, { target:'chart', ...query }),
				api.get(  `/api/2/report`, { target:'map',   ...query }),
				api.get(  `/api/2/report`, { target:'trend', ...query }),
				api.get(  `/api/2/report`, { target:'table', ...query }),
			]);
			console.log('Promises loaded', data);

			return { chart:data[0].report, map:data[1].report, trend:data[2].report, table:data[3].report };
		}
		return null;
	}
// async function getPromise( target, query ) {
// 	return api.report.get( Object.assign( { target:target }, query ) );
// }
	// function addToQuery( query, key, value ) {
// 	if( value ) query[ key ] = value; // only if > 0
// }

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