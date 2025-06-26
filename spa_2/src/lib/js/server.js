import { browser } from '$app/environment'; // to find window
import { ctx } from '$lib/js/store.svelte.js'

const API_BASE = browser && location.hostname === 'localhost' ? 'http://localhost:80' : ''; // dev vs prod

export function headers() {
	return {
		'Accept': 'application/json', // response
		'Content-Type': 'application/json', // body
		'Authorization': `Bearer ${ ctx.user ? ctx.user.token : '' }`,
	}
}

export async function get( url, query=null ) {
	url += query ? '?' + new URLSearchParams( query ).toString() : '';
	const response = await fetch( `${API_BASE}${url}`, { method:'GET', headers:headers() });
	return response.ok ? await response.json() : null;
}
export async function query( url, query ) {
	url += query ? '?' + new URLSearchParams( query ).toString() : '';
	const response = await fetch( `${API_BASE}${url}`, { method:'GET', headers:headers() });
	return response.ok ? await response.json() : null;
}

export async function post(url, body) {
	const response = await fetch(`${API_BASE}${url}`, { method:'POST', headers:headers(), body:JSON.stringify( body ) });
	return response.ok ? await response.json() : null;
}

export async function put(url, body) {
	const response = await fetch(`${API_BASE}${url}`, { method:'PUT', headers:headers(), body:JSON.stringify( body ) });
	return response.ok ? await response.json() : null;
}

export async function del(url) { // delete is a reserved word
	const response = await fetch(`${API_BASE}${url}`, { method:'DELETE', headers:headers() } );
	return response.ok ? await response.json() : null;
}

export default {
	get:get, query:query, post:post, put:put, delete:del,
}



