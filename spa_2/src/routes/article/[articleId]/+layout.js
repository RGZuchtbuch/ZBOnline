import api from '$lib/js/api.js';

export async function load ({ params }) {
	const response = await api.article.get( params.articleId );
	return { article:response.article }
}