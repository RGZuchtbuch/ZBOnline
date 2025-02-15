
import api from '$lib/js/api.js';
//import { app } from '$lib/js/store.svelte.js';

export async function load( { params } ) {
		const response = await api.district.get( params.districtId );
		console.log( 'District', response.district );
		return { district:response.district };
};