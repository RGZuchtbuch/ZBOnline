import model from '$lib/js/model.js';
import { ctx } from '$lib/js/store.svelte.js';

export async function load( { depends, fetch, params, parent } ) {
	depends( 'user' );
	console.log( 'Loading Moderator' );
	const { federation, user } = await parent(); // wait for these to have loaded
	let districts = [];
	if( federation && user ) {
		user.moderator.forEach(id => {
			districts.push(federation.districts[id]);
		});
	}
	return { districts:districts };
}
