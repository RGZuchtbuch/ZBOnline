import api from '$lib/js/server.js';
import model from '$lib/js/model.js';

export default class Pair {
	static async new( breeder ) {
		console.log( 'Br', breeder );
		return {
			id: 0, breederId: breeder.id, districtId: breeder.districtId,
			year: new Date().getFullYear(), group: 'I',
			name: null,
			sectionId: null, breedId: null, colorId: null,
			paired: null, notes: null,
			parents: [],
			lay: { id:0, pairId:0, start:null, end:null, dames:null, eggs:null, weight:null },
			broods: [],
			show: { id:0, pairId:0, scores: { 89:null, 90:null, 91:null, 92:null, 93:null, 94:null, 95:null, 96:null, 97:null } },
			breeder: breeder, // needed ??
			accepted: true, // by moderator
		}
	}

	static async load( id ){
		console.log( "Model Load pair", id );
		let data= await api.get(`/api/2/pair/${id}`);
		if( data && data.pair ) {
			let pair = data.pair;

			if ( pair.lay === null) pair.lay = {
				id: 0,
				pairId: pair.id,
				start: null,
				end: null,
				dames: null,
				eggs: null,
				weight: null
			};
			if ( pair.show === null) pair.show = {
				id: 0,
				pairId: pair.id,
				scores: {89: null, 90: null, 91: null, 92: null, 93: null, 94: null, 95: null, 96: null, 97: null}
			}
			return pair;
		}
		return null;
	}

	static async query( args ){
		const data = await api.query(`/api/2/pair`, args );
		return data && data.pairs ? data.pairs : null;
	}

	static async save( pair ){
		let ok = false;
		if( pair.id === 0 ) { // new
			const data = await api.post( `/api/2/pair`, pair );
			if( data && data.id > 0 ) {
				pair.id = data.id;
				ok = true;
			}
		} else { // existing
			const data = await api.put( `/api/2/pair/${pair.id}`, pair );
			if( data && data.id > 0 ) {
				ok = true;
			}
		}
		return ok;
	}

	static async delete( id ){
		console.log( 'Delete pair', id );
		let ok = false;
		if( id > 0 ) {
			ok = await api.delete( `/api/2/pair/${id}` );
		}
		return ok;
	}
}