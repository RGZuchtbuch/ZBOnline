
import api from '$lib/js/api.js';
//import { app } from '$lib/js/store.svelte.js';

export async function load( { params } ) {
		const response = await api.pair.get( { breederId:params.breederId } );
		console.log( 'R', response.pairs );
		return { pairs:response.pairs };
};