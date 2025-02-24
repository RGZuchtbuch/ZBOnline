import api from '$lib/js/api.js';

export async function load() {
	const response = await api.article.get();
	return { articles:response.articles };
};