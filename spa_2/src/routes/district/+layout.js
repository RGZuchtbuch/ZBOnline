import api from '$lib/js/api.js';
import {page} from '$app/state';

export async function load( { params } ) {
	console.log( 'Load articles' );
	if( params.articleId === 0 ) { // new article
		return { article:{ id:0, title:'Todo', author:$user.firstname, html:'Todo' }};
	} else { // fetch article by id
		const response = await api.article.get( params.articleId );
		if (response && response.article) {
			return { article:response.article };
		}
	}
	return { article:{ id:0, title:'Unbekannter Beitrag !', author:null, html:'...' } };
}

