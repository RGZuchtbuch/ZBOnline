import { error } from '@sveltejs/kit';

export function load({ params }) {
	const id = +params.districtId;
	return {
		breeder: { id:1, firstname:'Eelco', infix:'', lastname:'Jannink', districtId:1 }
	};
//	error(404, 'Not found');
}