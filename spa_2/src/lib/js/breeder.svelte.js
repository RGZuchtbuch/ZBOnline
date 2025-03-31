import api from '$lib/js/server.js';

export class Breeder {
	static async load( id ){
		console.log( "Load breeder", id );
		let article = null;
		const data = await api.get(`/api/2/breeder/${id}` );
		return data && data.breeder ? data.breeder : null;
	}
	static async query( args ){
		console.log( 'Load breeders', args );
		const data = await api.query(`/api/2/breeder`, args );
		return data && data.breeders ? data.breeders : null;
	}
	static async save( article ){
		console.log( 'Save article', article );
		if( article.id === 0 ) { // new
			const data = await api.post( `/api/2/article`, article );
			if( data && data.id > 0 ) {
				article.id = data.id;
				return true;
			}
		} else { // existing
			const data = await api.put( `/api/2/article/${article.id}`, article );
			if( data && data.id > 0 ) {
				return true;
			}
		}
		return false;
	}
	static async delete( id ){
		console.log( 'Delete article', id );
		return false; // TODO
	}
}