import api from '$lib/js/api.js';
import { headers } from '$lib/js/api.js';

export async function load ({ fetch, params }) {
	const articleId = params.articleId;
	const response = await fetch( `http://localhost:80/api/2/article/${articleId}`, headers() );
	const json = await response.json();
	return { article:json.article }

//
// 	await get(`/api/2/article/${arg}`); // { id, title, level }
//
// 	async function get( url, query=null ) {
// 		url += query ? '?' + new URLSearchParams( query ).toString() : '';
// 		const response = await fetch( `${API_BASE}${url}`, { method:'GET', headers:getHeaders() });
// 		return response.ok ? await response.json() : null;
// 	}
//
// //	let response = await api.article.get( params.articleId );
// //	let article = response.article;
// //		app.title = article.title;
// //		app.menu.trail.push( {name: article.title.substring(0, 8)+'..', href:null} );
// 	return { article: article };
}