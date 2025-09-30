import api from '$lib/js/server.js';
import model from '$lib/js/model.js';
import {cfg} from '$lib/js/store.svelte.js';

export default class Pair {
	static async new( breeder ) {
		console.log( 'Br', breeder );
		let pair = {
			id: 0, breederId: breeder.id, districtId: breeder.districtId,
			year: new Date().getFullYear(), group: 'I',
			name: null,
			sectionId: null, breedId: null, colorId: null,
			paired: null, notes: null,
			parents: [],
			lay: { id:0, pairId:0, start:null, end:null, dames:null, eggs:null, average: null, weight:null },
			broods: [],
			show: { id:0, pairId:0, scores: { 89:null, 90:null, 91:null, 92:null, 93:null, 94:null, 95:null, 96:null, 97:null } },
			breeder: breeder, // needed ??
			accepted: true, // by moderator
		}

		for (let i = pair.parents.length; i < 2; i++) {
			pair.parents.push( Pair.newParent( pair ) );
		}

		for (let i = pair.broods.length; i < 3; i++) { // minimum of 4
			pair.broods.push( Pair.newBrood(pair) );
		}

		return pair;
	}

	static newBrood( pair ) {
		return { id:0, pairId:pair.id, start:null, eggs:null, fertile:null, hatched:null, chicks:[] }
	}
	static newParent( pair ) {
		const index = pair.parents.length;
		return { id:0, pairId:pair.id, sex:index===0?'1.0':'0.1', ring:null, score:null, parentsPairId:null };

	}

	static async load( id ){
		let data= await api.get(`/api/2/pair/${id}`);
		if( data && data.pair ) {
			let pair = data.pair;

			for (let i = pair.parents.length; i < 2; i++) {
				pair.parents.push( Pair.newParent( pair ) );
			}

			if ( pair.lay === null) pair.lay = {
				id: 0,
				pairId: pair.id,
				start: null,
				end: null,
				dames: null,
				eggs: null,
				average: null, // virtual field, is eggs in db.pair.lay with no start
				weight: null
			};

			for (let i = pair.broods.length; i < ( pair.sectionId === cfg.pigeons ? 2 : 4 ); i++) { // minimum of 4
				pair.broods.push( Pair.newBrood(pair) );
			}

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