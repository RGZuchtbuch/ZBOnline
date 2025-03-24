import api from '$lib/js/api.js';

export async function load( { params } ) {
	let response = await api.district.get( +params.districtId );
	return { district:response.district };
}
