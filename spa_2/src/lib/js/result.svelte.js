
import api from '$lib/js/server.js';
import {invalidate} from '$app/navigation';

export class Result {
	static async load( id ){
		console.log( "Load result", id );
		const data = await api.get(`/api/2/result/${id}` );
		return data && data.result ? data.result : null;
	}

	static async query( args ) {
		const response = await api.get( `/api/2/result`, args );
		return response; // should have for breed and for colors
	}

	static async save( result ){
		console.log( 'Save result', result );
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
		console.log( 'Delete result', id );
		const data = await api.delete( `/api/2/result/${id}` );
		return data && data.deleted;
	}

}

// function newResult() {
// 	return {
// 		id:0, breeder:null, breeders:null, districtId:district.id, group:group, pairId:null, pairs:null, year:year,
// 		sectionId:section.id, breedId:breed.id, colorId:null, aocColor:null,
// 		lay:{ dames:null, eggs:null, weight:null },
// 		brood:{ eggs:null, fertile:null, hatched:null},
// 		show:{ count:null, score:null },
// 	}
// }