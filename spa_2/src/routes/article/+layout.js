import api from '$lib/js/api.js';
import {page} from '$app/state';

export async function load( { params } ) {
	console.log( 'Load articles' );
	const response = await api.article.get();
	if( response && response.articles ) {
		return { articles:response.articles };
	}
	return { articles:[] };
}
