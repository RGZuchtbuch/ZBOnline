
import api from '$lib/js/server.js';

export default class Breeder {
	static async load( id, districtId ){
		//console.log( "Load breeder", id, districtId );
		if( id === 0 ) { // new breeder
			return {
				id:0,
				member:null, firstname:null, infix:null, lastname:null,
				email:null, districtId:districtId, club:null,
				start:null, end:null,
				info:null,
			}
		} else {
			let article = null;
			const data = await api.get(`/api/2/breeder/${id}`);
			return data && data.breeder ? data.breeder : null;
		}
	}
	static async query( args ){
		//console.log( 'Load breeders', args );
		const data = await api.query(`/api/2/breeder`, args );
		return data && data.breeders ? data.breeders : null;
	}
	static async save( breeder ){
		console.log( 'Save breeder' );
		if( breeder.id === 0 ) { // new
			const data = await api.post( `/api/2/breeder`, breeder );
			if( data && data.id > 0 ) {
				breeder.id = data.id;
				return true;
			}
		} else { // existing
			const data = await api.put( `/api/2/breeder/${breeder.id}`, breeder );
			if( data && data.id > 0 ) {
				return true;
			}
		}
		return false;
	}
	static async delete( id ){
		console.log( 'Delete breeder', id, 'TODO' );
		return true; // TODO
	}
}