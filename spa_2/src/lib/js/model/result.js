
import api from '$lib/js/server.js';
import {invalidate} from '$app/navigation';

export default class Result {
	static async load( id ){
		const data = await api.get(`/api/2/result/${id}` );
		return data && data.result ? data.result : null;
	}

	static async query( args ) {
		const response = await api.get( `/api/2/result`, args );
		return response.results; // should have for breed and for colors
	}

	static async loadBreedersResults( districtId, year ) {
		const response = await api.get( `/api/2/result/breeder`, { district:districtId, year:year } );
		return response.results; // all districts breeders with results per breed/color
	}

	static async save( result ){
		if( result.id > 0 ) { // existing
			const data = await api.put( `/api/2/result/${result.id}`, result );
			if( data && data.id > 0 ) {

				return true;
			}
		} else {// new
			const data = await api.post( `/api/2/result`, result );
			if( data && data.id > 0 ) {
				result.id = data.id;
				return true;
			}
		}
		return false;
	}
	static async delete( id ){
		const data = await api.delete( `/api/2/result/${id}` );
		return data && data.deleted;
	}

}
