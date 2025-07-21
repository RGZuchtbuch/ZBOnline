import {invalidate, invalidateAll} from '$app/navigation';
import api from '$lib/js/server.js';

export default class Article {

	static invalid = true;

	static async load( id ){
		console.log( "Load article", id );
		if( id === 0 ) {
			return { id:0, level:1, author:null, title:null, html:null }
		} else {
			const data = await api.get(`/api/2/article/${id}`);
			return data && data.article ? data.article : null;
		}
	}
	static async query( args ){
		console.log( 'Load articles', args );
		const data = await api.query(`/api/2/article`, args );
		return data && data.articles ? data.articles : null;
	}
	static async save( article ){
		console.log( 'Save article', article.id );
		if( article.id === 0 ) { // new
			const data = await api.post( `/api/2/article`, article );
			if( data && data.id > 0 ) {
				article.id = data.id;
				await invalidate( 'articles' );

				return true;
			}
		} else { // existing
			const data = await api.put( `/api/2/article/${article.id}`, article );
			if( data && data.id > 0 ) {
				console.log('Updated article' );
				await invalidate( 'articles' );
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