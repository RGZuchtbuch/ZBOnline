import { browser } from '$app/environment'; // to find window
import {jwtDecode} from 'jwt-decode';
import api from '$lib/js/server.js';
import { ctx } from '$lib/js/store.svelte.js';

export default class User {

	// vars for login timeout
	static timeout = null;
	//static alerted = false;
	//static remaining = 0;


	static async load() {
		let token = browser && window.sessionStorage.getItem('token'); // encoded
		let user = tokenToUser( token );
		return user;
	}
	static async login ( email, password ) {
		const response = await api.post( '/api/2/user/login', { email:email, password:password } );
		if( response && response.token ) { // logged in ok
			ctx.user = tokenToUser( response.token );
			browser && window.sessionStorage.setItem( 'token', response.token );
		} else { // failed to login, so logout
			ctx.user = null;
			browser && window.sessionStorage.removeItem( 'token' );
		}
		return ctx.user;
	}

	static async forgot( email ) { // forgot password
		const response = await api.post( '/api/2/user/forgot', { email:email } );
		return true; // TODO
	}

	static async reset( token, password ) { // forgot password
		const response = await api.post( '/api/2/user/reset', { token:token, password:password } );
		if( response && response.token ) {
			ctx.user = tokenToUser( response.token );
			browser && window.sessionStorage.setItem( 'token', response.token );
			return true;
		} else {
			ctx.user = null;
			browser && window.sessionStorage.removeItem( 'token' );
			return false;
		}
	}

	static async logout() {
		ctx.user = null;
		window.sessionStorage.removeItem( 'token' ); // forget token
		ctx.menustate = { ...ctx.initialMenustate }; // reset menu state
		return true; // always successfully
	}

};


// privates
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
		console.error( error ); // TODO on faulty token ?
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

clearInterval( User.timeout ); // clear evt existing
User.timeout = setInterval( () => { // check if session timeout nears
	if( ctx.user ) {
		let now = Math.floor( Date.now() / 1000 );
		ctx.remaining = ctx.user.exp - now;
		//console.log( 'User TO', ctx.remaining );
		if( ctx.remaining < 60 ) { // last minute to avoid save errors
			User.logout();
		} else if( ctx.remaining < 3600 ) { // one hour
			if( ! ctx.alerted ) {
				alert("Du hast noch einer Stunde um erneut an zu melden");
				ctx.alerted = true;
			}
		} else {
			ctx.alerted = false;
		}
	}
}, 1000 );