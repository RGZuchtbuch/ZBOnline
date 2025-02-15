
import api from '$lib/js/api.js';
//import { app } from '$lib/js/store.svelte.js';

export async function load( { params } ) {
		const response = await api.breeder.get( params.breederId );
		console.log( 'Breeder', response.breeder );
		return { breeder:response.breeder };
};