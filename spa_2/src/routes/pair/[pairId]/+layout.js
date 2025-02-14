import { error } from '@sveltejs/kit';

/** @type {import('./$types').PageLoad} */
export function load({ params }) {
	console.log( 'Load', params );
	const pairId = +params.pairId;
	return {
		pairId:pairId,
		pair: {
			id: params.pairId,
			name: 'Pair C1',
			district: 1, breeder:1, year:2023
		}
	};
//	error(404, 'Not found');
}