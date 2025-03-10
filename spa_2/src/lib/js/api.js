
import { browser } from '$app/environment'; // to find window
import { jwtDecode } from 'jwt-decode';
import store from '$lib/js/store.svelte.js';
//let token = window.sessionStorage.getItem('token' ); // for auth api calls, may expire
//export let user = writable( extractUser( token ) ); //get stored user or empty
//console.log( 'User', extractUser( token ) );

let cache = {}; // empty at start

// retrieve token from sessionstorage, like for when refreshed
let authenticationToken = browser && window.sessionStorage.getItem( 'token' ); // encoded
if( authenticationToken ) store.user.update( () => tokenToUser( authenticationToken ) );

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
			return await post( `/api/2/article`, article ); // { id, title, level }
		},
		put: async ( id, article ) => {
			return await put( `/api/2/article/${id}`, article ); // { id, title, level }
		},
		delete: async ( id ) => {
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
		put: async ( id, pair ) => {
			return await put( `/api/2/pair/${id}`, pair );
		},
		post: async ( pair ) => {
			return await post( `/api/2/pair`, pair );
		},
		del: async ( id ) => {
			return await del( `/api/2/pair/${id}` );
		},
	},

	report : {
		get: async ( query ) => {
			console.log( 'Report', query );
			return await get( '/api/2/report', query )
		},
	},

	result : {
		get: async ( arg=null ) => {
			if( +arg ) { // test if number
				return await get(`/api/2/result/${arg}`); // { id, title, level }
			} else {
				return await get( `/api/2/result`, arg ); // arg as query
			}
		},
		// forColor: async ( districtId, year, colorId ) => {
		// 	return await get( `/api/2/result?district=${districtId}&year=${year}&color=${colorId}`);
		// },
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
			authenticationToken = null;
			window.sessionStorage.setItem( 'token', authenticationToken );
			const response = await post( '/api/2/user/login', { email:email, password:password } );
			if( response && response.token ) {
				console.log( 'Got Token' );
				authenticationToken = response.token; // to send with requests
				window.sessionStorage.setItem( 'token', authenticationToken ); // over session
				store.user.update( () => tokenToUser( response.token ) ); // user data for in app
				return true;
			}
			console.log( 'Login Oops')
			return false; // no user
		},
		forgot: async ( email ) => {
			return await post( '/api/2/user/forgot', { email:email } );
		},
		logout: async ( email ) => {
			authenticationToken = null;
			window.sessionStorage.setItem( 'token', null ); // forget token
			store.user.update( () => null ); // no user
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
	const response = await fetch( `${API_BASE}${url}`, { method:'GET', headers:headers });
	return response.ok ? await response.json() : null;
}

async function post(url, body) {
	const response = await fetch(`${API_BASE}${url}`, { method:'POST', headers:headers, body:JSON.stringify( body ) });
	return response.ok ? await response.json() : null;
}

async function put(url, body) {
	const response = await fetch(`${API_BASE}${url}`, { method:'PUT', headers:headers, body:JSON.stringify( body ) });
	return response.ok ? await response.json() : null;
}

async function del(url) { // delete is a reserved word
	const response = await fetch(`${API_BASE}${url}`, { method:'DELETE', headers:headers } );
	return response.ok ? await response.json() : null;
}

// Helpers
const headers = {
	'Accept': 'application/json', // response
	'Content-Type': 'application/json', // body
	'Authorization': `Bearer ${authenticationToken}`,
}

// session tools

export default api;

// export function headers( method='GET' ) {
// 	return {
// 		method:method,
// 		headers: {
// 			'Accept': 'application/json', // response
// 			'Content-Type': 'application/json', // body
// 			'Authorization': `Bearer ${authenticationToken}`,
// 		}
// 	}
// }


