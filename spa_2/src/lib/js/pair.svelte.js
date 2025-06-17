import api from '$lib/js/server.js';
import {Breeder} from '$lib/js/breeder.svelte.js';

export class Pair {
	static async load( id, breederId, districtId ){
		console.log( "Load pair", id, breederId, districtId	);
		let data = null;
		if( id === 0 ) {
			//const breeder = await Breeder::load( breederId );
			const breeder = await Breeder.load( breederId );
			data = {
				pair: {
					id: 0, breederId: breederId, districtId: districtId,
					year: new Date().getFullYear(), group: 'I',
					name: null,
					sectionId: null, breedId: null, colorId: null,
					paired: null, notes: null,
					parents: [],
					lay: { start:null, end:null, dames:null, eggs:null, weight:null },
					broods: [],
					show: {
						scores: { 89:null, 90:null, 91:null, 92:null, 93:null, 94:null, 95:null, 96:null, 97:null },
					},
					breeder: breeder,
					accepted: true,
				}
			}
		} else {
			data = await api.get(`/api/2/pair/${id}`);
		}

		return data && data.pair ? data.pair : null;
	}
	static async query( args ){
		const data = await api.query(`/api/2/pair`, args );
		return data && data.pairs ? data.pairs : null;
	}
	static async save( pair ){
		if( pair.id === 0 ) { // new
			const data = await api.post( `/api/2/pair`, pair );
			if( data && data.id > 0 ) {
				pair.id = data.id;
				return true;
			}
		} else { // existing
			const data = await api.put( `/api/2/pair/${pair.id}`, pair );
			if( data && data.id > 0 ) {
				return true;
			}
		}
		return false;
	}
	static async delete( id ){
		console.log( 'Delete pair', id );
		if( id > 0 ) {
			const data = await api.delete( `/api/2/pair/${id}` );
		}
		return false; // TODO
	}
}