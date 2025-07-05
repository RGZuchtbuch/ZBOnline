import api from '$lib/js/server.js';
import { ctx } from '$lib/js/store.svelte.js'

// private

let federation = null;

function districtLookup( federation ) {
	const districts = {};
	addDistrict( federation, districts ); // create district lookup by id
	return districts;
}
function addDistrict( district, districts ) { // recursive for sections
	districts[ district.id ] = district;
	for( const child of district.children ) {
		addDistrict( child, districts );
	}
}

export default class Federation {

	static async load() {
		if( federation === null ) {
			//federation = null; // { bdrg object, districts }
			const data = await api.get(`/api/2/district`, {rootId: 1}); // root is BDRG #1
			if (data && data.root) {
				federation = data.root; // district tree
				federation.districts = districtLookup(federation); // list of districts by id
			}
		}
		return federation; // null if failed
	}

}

export class District {
	static async load( id ) {
		return store.federation.districts[ id ];
//		return store.federation.districts[ +page.params.district ];
	}
	static async save( district ) {
		console.log( 'District.save');
		// TODO
		return null; // or saved id;
	}
}


// federation = $state( {
// 	value:null,
// 	districts:null,
//
// 	load: async ( query ) => {
// 		federation.value = null;
// 		const data = await api.get( `/api/2/district`, { rootId:1 } ); // root is BDRG #1
// 		if( data && data.district ) {
// 			federation.value = data.district, // district tree
// 			federation.districts = [];
// 			// add list of districts by id
// 			addDistrict( federation.value, federation.districts );
// 			return true;
// 		}
// 		return false;
// 	},
// 	district: {
// 		load: async ( id ) => {
// 			console.log( "Load district", id );
// 			district.value = null;
// 			const data= await api.get(`/api/2/district/${id}` );
// 			if( data && data.district ) {
// 				district.value = data.district;
// 				return true;
// 			}
// 			//article.value = await api.article.get( +id );
// 			return false;
// 		},
// 		save: async () => {
// 			// save from value;
// 			if( district.id === 0 ) {
// 				const data = await api.post( `/api/2/district`, district.value );
// 				if( data && data.id ) {
// 					district.value.id = data.id;
// 				}
// 			} else {
// 				api.put( `/api/2/district/${district.value.id}`, district.value );
// 			}
// 			return 99; // new id, id  or 0;
// 		},
// 		delete: async () => {
// 			return true; // TODO
// 		},
// 	}
//
// } );
//
// export default federation;