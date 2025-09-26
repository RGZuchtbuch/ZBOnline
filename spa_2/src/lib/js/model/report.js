import api from '$lib/js/server.js';

export default class Report {
	// static async query( args ) {
	// 	if( args && args.district && args.year ) { // must haves
	// 		const response = await Promise.all( [
	// 			api.get(  `/api/2/report`, { target:'chart', ...args }),
	// 			api.get(  `/api/2/report`, { target:'map',   ...args }),
	// 			api.get(  `/api/2/report`, { target:'trend', ...args }),
	// 			api.get(  `/api/2/report`, { target:'table', ...args }),
	// 		]);
	//
	// 		return {
	// 			chart:response[0].report,
	// 			map:response[1].report,
	// 			trend:response[2].report,
	// 			table:response[3].report,
	// 		};
	// 	}
	// 	return null;
	// }

	static async query(args ) {
		args = Object.fromEntries( Object.entries( args ).filter( ( [ key, value ] ) => value !== null ) );
		if( args ) { // must haves
			let response = await api.get(  `/api/2/report`, args );
			if( response ) {
				return response.report;
			}
		}
		return null;
	}
	// static async loadTable( args ) {
	// 	if( args && args.district && args.year ) { // must haves
	// 		let response = await api.get(  `/api/2/report`, { target:'table', ...args });
	// 		if( response ) {
	// 			return response.report;
	// 		}
	// 	}
	// 	return null;
	// }
	// static async loadTable( args ) {
	// 	if( args && args.district && args.year ) { // must haves
	// 		let response = await api.get(  `/api/2/report`, { target:'table', ...args });
	// 		if( response ) {
	// 			return response.report;
	// 		}
	// 	}
	// 	return null;
	// }
	//
	// static async loadTable( args ) {
	// 	if( args && args.district && args.year ) { // must haves
	// 		let response = await api.get(  `/api/2/report`, { target:'table', ...args });
	// 		if( response ) {
	// 			return response.report;
	// 		}
	// 	}
	// 	return null;
	// }
}
