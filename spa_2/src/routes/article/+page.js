import api from '$lib/js/api.js';
import { app } from '$lib/js/store.svelte.js';

export async function load() {
	if( app.articles ) {
		console.log('LA app');
		return {articles: app.articles};
	} else {
		const response = await api.article.get();
		console.log( 'LA')
		return {articles: response.articles};
	}
};