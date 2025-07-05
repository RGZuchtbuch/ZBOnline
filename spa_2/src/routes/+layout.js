export const ssr = false; // need this once for spa only in sveltekit
import {ctx} from '$lib/js/store.svelte.js';

//import { setContext } from 'svelte';
import model from '$lib/js/model.js';

export async function load( { depends, fetch, params } ) {
	depends( 'user' );
	depends( 'federation' )
	depends( 'standard' );
	const response = await Promise.all([
		model.Federation.load(),
		model.Standard.load(),
		model.User.load() // gets it from sessionStorage
	]);
	return {
		federation: response[0],
		standard: response[1],
		user: response[2]
	};
}
