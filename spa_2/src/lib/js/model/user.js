import { browser } from '$app/environment'; // to find window
import {jwtDecode} from 'jwt-decode';
import api from '$lib/js/server.js';
import { ctx } from '$lib/js/store.svelte.js';
//import {getContext} from 'svelte'; // cannot be here

//let ctx = getContext( 'ctx' );


export default class User {
	static async load() {
		let token = browser && window.sessionStorage.getItem('token'); // encoded
		// store.user = tokenToUser( token );
		// return store.user;
		return tokenToUser( token );
	}
	static async login ( email, password ) {
		const response = await api.post( '/api/2/user/login', { email:email, password:password } );
		if( response && response.token ) {
			console.log( 'Got Token' );
			ctx.user = tokenToUser( response.token );
			browser && window.sessionStorage.setItem( 'token', response.token );
		} else {
			ctx.user = null;
			browser && window.sessionStorage.removeItem( 'token' );
		}
		return store.user;
	}
	static async forgot( email ) { // forgot password
		const response = await api.post( '/api/2/user/forgot', { email:email } );
		console.log( response );
		return true; // TODO
	}
	static async logout() {
		ctx.user = null;
		window.sessionStorage.removeItem( 'token' ); // forget token
		return true; // always successfull
	}
};

function tokenToUser( token ) {
	let user = null;
	try {
		if (token) {
			const decoded = jwtDecode(token);
			user = decoded.user;
			user.exp = decoded.exp;
			user.token = token;
		}
	} catch ( error ) {
		console.error( error );
	}
	return user; // failed decode
}

function useSessionToken() {
	let token = browser && window.sessionStorage.getItem('token'); // encoded
	if (token) {
		ctx.user = tokenToUser( token );
	}
}

useSessionToken(); // gets from sessionstorage if available and not exp.