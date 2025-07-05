//import { error } from '@sveltejs/kit';

export function load({ params }) {
	console.log( 'Load', params );
	const id = +params.breederId;
	return {
		pairs: [
			{ id:1, name:'A', districtId:1 },
			{ id:2, name:'B', districtId:2 },
		]
	};
//	error(404, 'Not found');
}