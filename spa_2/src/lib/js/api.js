
import { browser } from '$app/environment'; // to find window
import { jwtDecode } from 'jwt-decode';
import { app } from '$lib/js/store.svelte.js';
//let token = window.sessionStorage.getItem('token' ); // for auth api calls, may expire
//export let user = writable( extractUser( token ) ); //get stored user or empty
//console.log( 'User', extractUser( token ) );

let cache = {}; // empty at start

let authenticationToken = browser && window.sessionStorage.getItem( 'token' ); // encoded


const API_BASE = browser && (location.hostname === 'localhost' || location.hostname === '127.0.0.1') ? 'http://localhost:80' : 'https://rgzuchtbuch.de';

const api = { // api
	article: {
		//all: async () => { // index of all article { id, title }
		//	return await get( `/api/article` );
		//},
		get: async ( arg=null ) => {
			if( +arg ) { // test if number
				return await get(`/api/2/article/${arg}`); // { id, title, level }
			} else {
				return await get( `/api/2/article`, arg ); // arg as query
			}
		},
		post: async ( article ) => {
			//console.log( 'article.post not implemented yet' );
			return await post( `/api/2/article`, article ); // { id, title, level }
		},
		put: async ( id, article ) => {
			//console.log( 'article.put not implemented yet' );
			return await put( `/api/2/article/${id}`, article ); // { id, title, level }
		},
		delete: async ( id ) => {
			//console.log( 'article.del not implemented yet' );
			return await del( `/api/2/article/${id}` ); // { ok }
		}
	},

	breed : {
		forSection: async ( sectionId ) => {
			return await get( `/api/2/breed?section=${sectionId}`);
		}
	},

	breeder : {
		get: async ( arg=null ) => {
			if( +arg ) { // test if number
				return await get(`/api/2/breeder/${arg}`); // { id, title, level }
			} else {
				return await get( `/api/2/breeder`, arg ); // arg as query
			}
		},
	},

	color : {
		forBreed: async ( breedId ) => {
			return await get( `/api/2/color?breed=${breedId}`);
		}
	},
	district : {
		get: async ( arg=null ) => {
			if( +arg ) { // test if number
				return await get(`/api/2/district/${arg}`); // { id, title, level }
			} else {
				console.log( 'district query', arg )
				return await get( `/api/2/district`, arg ); // arg as query
			}
		},
	},

	pair : {
		get: async ( arg=null ) => {
			if( +arg ) { // test if number
				return await get(`/api/2/pair/${arg}`); // { id, title, level }
			} else {
				return await get( `/api/2/pair`, arg ); // arg as query
			}
		},
	},

	result : {
		forColor: async ( districtId, year, colorId ) => {
			return await get( `/api/2/result?district=${districtId}&year=${year}&color=${colorId}`);
		},
	},
	standard : {
		get: async ( arg=null ) => { // gets all
			if( false && cache.standard ) {
				return cache.standard;
			}
			if( ! cache.standard ) {
				const response = await get('/api/2/standard');
				if (response) {
					cache.standard = response;
				} else {
					return null; // empty
				}
			}
			return cache.standard;
		}
	},
	user : {
		login: async ( email, password ) => {
			const response = await post( '/api/2/user/login', { email:email, password:password } );
			if( response ) {
				window.sessionStorage.setItem( 'token', response.token );
				app.user = tokenToUser( response.token );
				return app.user;
			}
			console.log( 'Login Oops')
			return null; // no user
		},
		forgot: async ( email ) => {
			return await post( '/api/2/user/forgot', { email:email } );
		},
		logout: async ( email ) => {
			app.user = null;
			window.sessionStorage.setItem( 'token', null ); // forget token
			return true; // always successfull
		},

	},

}


/* private crud functions */

function tokenToUser( token ) {
	try {
		if (token) {
			const decoded = jwtDecode(token);
			decoded.user.exp = decoded.exp;
			return decoded.user; // authed user
		}
	} catch ( error ) {
		console.error( error );
	}
	return null;
}

function findRoot( data, id ) {
	for( item of data ) {
		if( item.id === id ) {
			return item;
		} else {
			const found = findRoot( item.children, id );
			if( found ) return found;
		}
	}
}



// fetch methods
async function get( url, query=null ) {
	url += query ? '?' + new URLSearchParams( query ).toString() : '';
	const response = await fetch( `${API_BASE}${url}`, { method:'GET', headers:getHeaders() });
	return response.ok ? await response.json() : null;
}

async function post(url, body) {
	const response = await fetch(`${API_BASE}${url}`, { method:'POST', headers:getHeaders(), body:JSON.stringify( body ) });
	return response.ok ? await response.json() : null;
}

async function put(url, body) {
	const response = await fetch(`${API_BASE}${url}`, { method:'PUT', headers:getHeaders(), body:JSON.stringify( body ) });
	return response.ok ? await response.json() : null;
}

async function del(url) { // delete is a reserved word
	const response = await fetch(`${API_BASE}${url}`, { method:'DELETE', headers:getHeaders() } );
	return response.ok ? await response.json() : null;
}

// Helpers
function getHeaders() {
	return {
		'Accept': 'application/json', // response
		'Content-Type': 'application/json', // body
		'Authorization': `Bearer ${authenticationToken}`,
	}
}

// session tools

export default api;

export function headers( method='GET' ) {
	return {
		method:method,
		headers: {
			'Accept': 'application/json', // response
			'Content-Type': 'application/json', // body
			'Authorization': `Bearer ${authenticationToken}`,
		}
	}
}


