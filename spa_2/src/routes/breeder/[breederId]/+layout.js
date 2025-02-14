import { error } from '@sveltejs/kit';

export function load({ params }) {
	console.log( 'Load', params );
	const id = +params.districtId;
	return {
		breeder: { id:1, firstname:'Eelco', infix:'', lastname:'Jannink', districtId:1 }
	};
//	error(404, 'Not found');
}