import api from '$lib/js/api.js';
import {store} from '$lib/js/store.svelte.js';

export async function load ({ fetch, params }) {
	const responses = await Promise.all( [ getPair( params.pairId ) ] )
	store.pair.set( responses[0] );
	return { pair:responses[0] };
}

async function getPair( id ) {
	const response = await api.pair.get( id );
	if( response ) {
		return response.pair;
	}
	return null;
}