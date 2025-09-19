import api from '$lib/js/server.js'; // get post put delete

let standard = null; //await Standard.load();

export default class Standard {
	static async load() {
		standard = null
		const data= await api.get(`/api/2/standard` );
		if( data && data.standard ) {
			standard = structuredStandardTree( data.standard ); // { root, sections, breed, colors }
			//standard.rootSections = getRootSections( standard )
			standard.rootSections = [
				standard.sections[ 3 ],
				standard.sections[ 11 ],
				standard.sections[ 12 ],
				standard.sections[ 13 ],
				standard.sections[ 5 ],
				standard.sections[ 6 ],
			];
		}
		return standard;
	}

	static createBreed( sectionId ) {
		return {
			id:0, name: 'Neu !', sectionId: sectionId,
			broodGroup:null, layEggs:null, layWeight:null,
			sireWeight:null, dameWeight:null, sireRing:null, dameRing:null,
			colors:[],
			// info:null,
		}
	}

	static createColor( breedId ) {
		return {
			id:0, name: 'Neu !', breedId: breedId,
			// info:null,
		}
	}

	static async saveBreed( breed ){
		//console.log( 'Save article', article.id );
		if( breed.id === 0 ) { // new
			const data = await api.post( `/api/2/standard/breed`, breed );
			if( data && data.id > 0 ) {
				breed.id = data.id; // use new id from db
				return true;
			}
		} else { // existing
			const data = await api.put( `/api/2/standard/breed/${breed.id}`, breed );
			if( data && data.id > 0 ) {
				return true;
			}
		}
		return false;
	}

	static async deleteBreed( id ) {
		console.log( 'Delete breed', id );
		let ok = false;
		if( id > 0 ) {
			ok = await api.delete( `/api/2/standard/breed/${id}` );
		}
		return ok;
	}

	static async saveColor( color ){
		//console.log( 'Save article', article.id );
		if( color.id === 0 ) { // new
			const data = await api.post( `/api/2/standard/color`, color );
			if( data && data.id > 0 ) {
				color.id = data.id; // use new id from db
				return true;
			}
		} else { // existing
			const data = await api.put( `/api/2/standard/color/${color.id}`, color );
			if( data && data.id > 0 ) {
				return true;
			}
		}
		return false;
	}

	static async deleteColor( id ) {
		console.log( 'Delete color', id );
		let ok = false;
		if( id > 0 ) {
			ok = await api.delete( `/api/2/standard/color/${id}` );
		}
		return ok;
	}
};


// // main sections to enter results for
// function getRootSections( standard ) {
// 	const sections = [
// 		{ id:3,  name:'Groß u. Wassergeflügel', breeds:[] },
// 		{ id:11, name:'Hühner Groß', breeds:[] },
// 		{ id:12, name:'Zwerghühner', breeds:[] },
// 		{ id:13, name:'LegeWachteln', breeds:[] },
// 		{ id:5,  name:'Tauben', breeds:[] },
// 		{ id:6,  name:'Ziergeflügel', breeds:[] },
// 	];
// 	// for( let section of sections ) { // each rootSection, add breed and child breeds
// 	// 	addBreeds(  standard.sections[ section.id ], section.breeds ); // add to rootsection breeds, recursive
// 	// 	section.breeds.sort((a, b) => a.name.localeCompare(b.name)); // sort by name
// 	// }
// 	return sections;
// }

// make sections, breeds and colors available by id
function structuredStandardTree( standard ) {
	standard.sections = {};
	standard.breeds = {};
	standard.colors = {};
	addSection( standard.root, standard );
	return standard;
}
// add sections, breeds and colors for sections incl subsections
function addSection( section, standard ) { // recursive for sections
	standard.sections[ section.id ] = section;
	for( const breed of section.breeds ) {
		standard.breeds[ breed.id ] = breed;
		for( const color of breed.colors ) {
			standard.colors[ color.id ] = color;
		}
	}
	for( const child of section.children ) { // nesting
		addSection( child, standard );
		addBreeds( child, section.breeds ); // add child breeds to this sections breeds
	}
	section.breeds.sort( ( a, b ) => a.name.localeCompare( b.name ));
}
function addBreeds( section, breeds ) { // for rootSections
	for( const breed of section.breeds ) { // add these
		breeds.push( breed );
	}
	for( const child of section.children ) { // add children's
		addBreeds( child, breeds );
	}
}


