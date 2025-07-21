import api from '$lib/js/server.js';
import { ctx } from '$lib/js/store.svelte.js'
import {invalidate} from '$app/navigation';

// private

let federation = null;

function districtLookup( federation ) {
	const districts = {};
	addDistrict( federation, districts ); // create district lookup by id
	return districts;
}
function addDistrict( district, districts ) { // recursive for sections
	districts[ district.id ] = district;
	for( const child of district.children ) {
		addDistrict( child, districts );
	}
}

export default class Federation {

	static async load() {
		if( federation === null ) {
			//federation = null; // { bdrg object, districts }
			const data = await api.get(`/api/2/district`, {rootId: 1}); // root is BDRG #1
			if (data && data.root) {
				federation = data.root; // district tree
				federation.districts = districtLookup(federation); // list of districts by id
			}
		}
		return federation; // null if failed
	}

	static async saveDistrict( district ) {
		console.log( 'Save district' );
		if( district.id === 0 ) {
			const response = await api.post( `/api/2/district`, district );
			if( response && response.id > 0 ) {
				district.id = response.id; // new id
				return true;
			}
		} else { // existing
			const response = await api.put( `/api/2/district/${district.id}`, district );
			if( response && response.id > 0 ) {
				return true;
			}
		}
		await invalidate( 'federation' );
		await invalidate( 'district' );
		return false;
	}
}
