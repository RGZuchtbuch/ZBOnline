import api from '$lib/js/server.js';
import { ctx, dirty } from '$lib/js/store.svelte.js'
import {invalidate} from '$app/navigation';

// private



function getDistricts( federation ) {
	const districts = {};
	getDistrictsRec( federation, districts ); // create district lookup by id
	return districts;
}
function getDistrictsRec( district, districts ) { // helper for getDistricts
	districts[ district.id ] = district;
	for( const child of district.children ) {
		getDistrictsRec( child, districts );
	}
}

export default class Federation {

	static async load() {
		let federation = null;
		const data = await api.get(`/api/2/district`, {rootId: 1}); // root is BDRG #1
		if (data && data.root) {
			federation = data.root; // district tree
			federation.districts = getDistricts(federation); // list of districts by id
		}
		return federation; // null if failed
	}

	static async saveDistrict( district ) {
		dirty.federation++;
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
		return false;
	}
}
