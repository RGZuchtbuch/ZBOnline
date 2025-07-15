import {invalidate} from '$app/navigation';
import api from '$lib/js/server.js';

export default class Article {

	static invalid = true;

	static async load( id ){
		return null;
	}
	static async query( args ){
		return null;
	}
	static async save( message ){
		console.log( 'Post message' );

		if( message.to > 0 ) { // new
			const response= await api.post( `/api/2/message`, message );
			if( response.success ) {
				return true;
			}
		}
		return false;
	}
	static async delete( id ){
		console.log( 'Delete article', id );
		await invalidate( 'article' );
		return false; // TODO
	}
}