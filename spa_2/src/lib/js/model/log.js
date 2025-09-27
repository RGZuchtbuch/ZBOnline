import {invalidate, invalidateAll} from '$app/navigation';
import { ctx, dirty } from '$lib/js/store.svelte.js';
import api from '$lib/js/server.js';

export default class Log {

	static invalid = true;

	// static async load( id ){
	// 	//console.log( "Load article", id );
	// 	if( id === 0 ) {
	// 		return { id:0, level:1, author:null, title:null, html:null }
	// 	} else {
	// 		const data = await api.get(`/api/2/article/${id}`);
	// 		return data && data.article ? data.article : null;
	// 	}
	// }

	static async query( args ){
		//console.log( 'Load articles', args );
		const data = await api.query(`/api/2/log`, args );
		return data && data.logs ? data.logs : null;
	}

	// static async save( article ){
	// 	//console.log( 'Save article', article.id );
	// 	if( article.id === 0 ) { // new
	// 		const data = await api.post( `/api/2/article`, article );
	// 		if( data && data.id > 0 ) {
	// 			article.id = data.id; // use new id from db
	// 			return true;
	// 		}
	// 	} else { // existing
	// 		const data = await api.put( `/api/2/article/${article.id}`, article );
	// 		if( data && data.id > 0 ) {
	// 			return true;
	// 		}
	// 	}
	// 	return false;
	// }
	//
	// static async delete( id ){
	// 	return await api.delete( `/api/2/article/${id}` );
	// }
}