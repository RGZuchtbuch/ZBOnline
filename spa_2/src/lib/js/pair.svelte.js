import api from '$lib/js/server.js';

export class Pair {
	static async load( id ){
		console.log( "Load pair", id );
		const data = await api.get(`/api/2/pair/${id}` );
		return data && data.pair ? data.pair : null;
	}
	static async query( args ){
		console.log( 'Load pairs', args );
		const data = await api.query(`/api/2/pair`, args );
		return data && data.pairs ? data.pairs : null;
	}
	static async save( pair ){
		console.log( 'Save pair', pair );
		if( pair.id === 0 ) { // new
			const data = await api.post( `/api/2/pair`, pair );
			if( data && data.id > 0 ) {
				pair.id = data.id;
				return true;
			}
		} else { // existing
			const data = await api.put( `/api/2/pair/${pair.id}`, pair );
			if( data && data.id > 0 ) {
				return true;
			}
		}
		return false;
	}
	static async delete( id ){
		console.log( 'Delete pair', id );
		return false; // TODO
	}
}