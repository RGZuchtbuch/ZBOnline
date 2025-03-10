import api from '$lib/js/api.js';
import store from '$lib/js/store.svelte.js';

export async function load ( { params } ) {
	const response = await api.article.get( params.articleId );
	if( response && response.article ) {
		store.article.update( () => response.article );
		return true;
	}
	return false;
//	return { article:response.article }
}