export const ssr = false; // need this once for spa only in sveltekit

//import { setContext } from 'svelte';
import { Federation, Standard, User } from '$lib/js/model.js';

export async function load( { depends, fetch, params } ) {
	// depends( 'federation', 'standard', 'user' );
	// const response = await Promise.all( [
	// 	Federation.load( fetch ),
	// 	Standard.load( fetch ),
	// 	User.load()
	// ] );
	//
	// return {
	// 	federation: response[0],
	// 	standard: response[1],
	// 	user: response[2],
	// 	header: { title:null, menu:{trail:[], options:[] } },
	// }
	return {};
}

/*
 * federation and standard are completely loaded for the app as they are frequently used all over
 * user loads from sessionstorage, if there
 */