import api from '$lib/js/api.js';

export async function load ({ fetch, params }) {
	const response = await api.pair.get( params.pairId );
	console.log( 'Pair', params.pairId, response.pair );
	return { pair:response.pair };
}
