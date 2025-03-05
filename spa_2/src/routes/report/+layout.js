import { goto } from '$app/navigation';
import {page} from '$app/state';
import api from '$lib/js/api.js';

export async function load( { params, url } ) {

	// should get
	// * map data
	// * trend data
	// * table data, also for pie and bar charts
	console.log("Loading Reports");

	const districtId= url.searchParams.get( 'district' );
	const year      = url.searchParams.get( 'year' );
	console.log("Loading Reports", districtId, year);

	if( districtId && year ) {
		let query = {};
		const entries = url.searchParams.entries()
		console.log( 'Entries', entries );
		for (const [key, value] of entries) {
			console.log( 'Entry', key, value );
			if (value) {
				query[key] = value;
			}
		}
		console.log('Load for Reports', query);

		const chartPromise = getPromise('chart', query);
		const mapPromise   = getPromise('map', query);
		const trendPromise = getPromise('trend', query);
		//const tablePromise = getPromise('table', query);

		const responses = await Promise.all([ chartPromise, mapPromise, trendPromise ])
		return { reports:{ chart:responses[0], map:responses[1], trend:responses[2] } }; // no return as all in stored state
	} else {
		url.searchParams.set( 'district', 1 );
		url.searchParams.set( 'year', (new Date().getFullYear()-1).toString() );
		await goto( url );
	}
}

async function getPromise( target, query ) {
	query.target=target;
	return api.report.get( query );
}