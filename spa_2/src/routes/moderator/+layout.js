import model from '$lib/js/model.js';

export async function load( { depends, fetch, params, parent } ) {
	depends( 'user' );
	console.log( 'Loading Moderator' );
	const { federation, user } = await parent();
	let districts = [];
	user.moderator.forEach( id => {
		districts.push( federation.districts[id] );
	});
	return { districts:districts };
}
