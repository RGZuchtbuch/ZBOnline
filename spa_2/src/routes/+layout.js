export const ssr = false; // need this once for spa only in sveltekit

import { Federation } from '$lib/js/federation.svelte.js';
import { Standard } from '$lib/js/standard.svelte.js';
import { User } from '$lib/js/user.svelte.js';

export async function load( { params } ) {
	const data = await Promise.all( [ Federation.load(), Standard.load(), User.load() ] );
	return { federation:data[0], standard:data[1], user:data[2] };
}

/*
 * federation and standard are completely loaded for the app as they are frequently used all over
 * user loads from sessionstorage, if there
 */