
import api from '$lib/js/server.js';

export class Result {
	static async load( id ){
		console.log( "Load result", id );
		const data = await api.get(`/api/2/result/${id}` );
		return data && data.result ? data.result : null;
	}

	static async query( args ) {
		const data = await api.get( `/api/2/result`, args );
		return data && data.results ? data.results : null;
	}
}