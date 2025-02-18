
import api from '$lib/js/api.js';
//import { app } from '$lib/js/store.svelte.js';

export async function load( { params } ) {
		const response = await api.breeder.get( params.breederId );
		return { breeder:response.breeder };
};