
import api from '$lib/js/api.js';
//import { app } from '$lib/js/store.svelte.js';

export async function load( { params, url } ) {
	const year= +url.searchParams.get( 'year' ) || new Date().getFullYear()-1;
	const response = await api.pair.get( { districtId:params.districtId, year:year } );
	return { pairs:response.pairs, year:year };
}

