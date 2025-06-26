
import Federation, {District} from '$lib/js/model/federation.svelte.js';
import { Standard } from '$lib/js/model/standard.svelte.js';
import { User } from '$lib/js/model/user.svelte.js';

export async function load( { params, parent } ) {
	let data = await parent(); // wait for federation to have loaded
	const district = data.federation.districts[ +params.district ];//store.federation.districts[ +page.params.district ];
	return { district:district };
}

/*
 * federation and standard are completely loaded for the app as they are frequently used all over
 * user loads from sessionstorage, if there
 */